<?php

namespace App\Http\Controllers;

use App\Models\Header;
use App\Models\Language;
use App\Models\ImageManager;
use App\Services\FileManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class HeaderSettingsController extends Controller
{
    /**
    * Dosya yöneticisi, upload, validations etc.
    *
    * @var \App\Services\FileManagerService
    */
    protected $fileManagerService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FileManagerService $fileManagerService)
    {
        $this->middleware('auth');
        $this->fileManagerService = $fileManagerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * User Preferences Sessions for language,
         */
        if(!session('lang_code')){
            session()->forget('lang_code');
            session()->put('lang_code', App::getLocale());
        }else{
            App::setLocale(session('lang_code'));
        }

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Sidebar selected item
         */
        session()->forget('selectedSideMenu');
        session()->put('selectedSideMenu',last(explode('/',URL::current())));
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Works with lang_code parameter;
         * if null default is "tr"
         * if not null and no content on that language routes to "add" page.
         * if not null and there is content for that language, routes to edit page with lang_code, content, langs, image sizes parameters.
         */
        $header = Header::where('lang_code', $request->lang_code)->get();
        if (0 == $header->count()) {
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Language parameter validations.
             */
            $request->validate([
                'lang_code' => 'required|alpha|min:2|max:3',
            ]);
            $allLanguages = Language::get();
            $isLanguageExist = false;
            foreach($allLanguages as $item){
                if($item->lang_code == $request->lang_code){
                    $isLanguageExist = true;
                }
            }
            if(!$isLanguageExist){
                $request->session()->flash('error', 'Kayıt Başarısız');
                return back();
            }

            $header = new Header();
            $header->lang_code = $request->lang_code;
            $header->save();
            return view('admin.header-settings-update', [
                'lang_code' => $request->lang_code,
                'content' => $header,
                'langs' => Language::orderBy('order','asc')->get(),
                'large_size' => ImageManager::where('type','header_resim_geniş')->first(),
                'mobile_size' => ImageManager::where('type','header_resim_mobil')->first(),
            ]);
        }
        return view('admin.header-settings-update', [
            'lang_code' => $request->lang_code,
            'content' => $header[0],
            'langs' => Language::orderBy('order','asc')->get(),
            'large_size' => ImageManager::where('type','header_resim_geniş')->first(),
            'mobile_size' => ImageManager::where('type','header_resim_mobil')->first(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Call the data.
         */
        $header = Header::findOrFail($id);
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Three different forms.
         */
        if($request->has('updateimagelarge')){
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Validation for file mime from FileManagerService.
             */
            if($request->hasFile('logo_large_screen')){
                if(!$this->fileManagerService->checkExtension($request->logo_large_screen)){
                    $request->session()->flash('file_extension_error','Dosya uzantısı.');
                    return back();
                }
            }

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Upload cropped image if there is one.
             */
            if ($request->cropped_data) {
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                * Quality, fize size, image sizes.
                */
                $imageManager = ImageManager::where('type', 'header_resim_geniş')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($header->title.'-l',$request->cropped_data,'logos',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is one.
                 */
                if (file_exists($header->logo_large_screen)) {
                    unlink($header->logo_large_screen);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $header->logo_large_screen = $result;
                if ($header->save()) {
                    $request->session()->flash('success', 'Header Güncellendi');
                } else {
                    $request->session()->flash('error', 'Header Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_large');    //Which tab will be opened.
            return back();
        }else if($request->has('updateimagesmall')){
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Validation for file mime from FileManagerService.
             */
            if($request->hasFile('logo_small_screen')){
                if(!$this->fileManagerService->checkExtension($request->logo_small_screen)){
                    $request->session()->flash('file_extension_error','Dosya uzantısı.');
                    return back();
                }
            }
            /** ►►►►► DEVELOPER ◄◄◄◄◄
            * Quality, fize size, image sizes.
            */
            if ($request->cropped_data_mobile) {
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                * Quality, fize size, image sizes.
                */
                $imageManager = ImageManager::where('type', 'header_resim_mobil')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($header->title.'-m',$request->cropped_data_mobile,'logos',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is one.
                 */
                if (file_exists($header->logo_small_screen)) {
                    unlink($header->logo_small_screen);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $header->logo_small_screen = $result;
                if ($header->save()) {
                    $request->session()->flash('success', 'Header Güncellendi');
                } else {
                    $request->session()->flash('error', 'Header Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_small');    //Which tab will be opened.
            return back();
        }else if($request->has('updateheadersettings')){

            $header->title = $request->title;
            $header->description = $request->description;
            $header->facebook = $request->facebook;
            $header->instagram = $request->instagram;
            $header->twitter = $request->twitter;
            $header->youtube = $request->youtube;
            $header->pinterest = $request->pinterest;
            $header->linkedin = $request->linkedin;
            $header->google = $request->google;

            if ($header->save()) {
                $request->session()->flash('success', 'Kayıt Başarılı');
            } else {
                $request->session()->flash('error', 'Kayıt Başarısız');
            }
            session()->flash('tab_page','context');                 //Which tab will be opened.
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
