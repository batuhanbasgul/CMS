<?php

namespace App\Http\Controllers;

use App\Models\Footer;
use App\Models\Language;
use App\Models\ImageManager;
use App\Services\FileManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class FooterSettingsController extends Controller
{
    /** ►►►►► DEVELOPER ◄◄◄◄◄
    * File Manager Service for upload, validations etc.
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
         * Sidebar selected item
         */
        session()->forget('selectedSideMenu');
        session()->put('selectedSideMenu',last(explode('/',URL::current())));

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
         * Works with lang_code parameter;
         * if null default is "tr"
         * if not null and no content on that language routes to "add" page.
         * if not null and there is content for that language, routes to edit page with lang_code, content, langs, image sizes parameters.
         */
        if (0 == Footer::all()->count()) {
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * route to "add" page
             */
            return view('admin.footer-settings-add', ['lang_code' => session('lang_code'), 'langs' => Language::all()]); //Parameter came from nav link
        } else {
            $footer = Footer::where('lang_code', $request->lang_code)->get();
            if (0 == $footer->count()) {
                return view('admin.footer-settings-add', ['lang_code' => $request->lang_code, 'langs' => Language::all()]);
            }
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * route to edit page with paremeters
             */
            return view('admin.footer-settings-update', [
                'lang_code' => $request->lang_code,
                'content' => $footer[0],
                'langs' => Language::orderBy('order','asc')->get(),
                'large_size' => ImageManager::where('type','footer_resim_geniş')->get()[0],
                'mobile_size' => ImageManager::where('type','footer_resim_mobil')->get()[0],
            ]);
        }
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

        $footer = new Footer();
        $footer->title = $request->title;
        $footer->description = $request->description;
        $footer->copy_right = $request->copy_right;
        $footer->lang_code = $request->lang_code;

        if ($footer->save()) {
            $request->session()->flash('success', 'Kayıt Başarılı');
        } else {
            $request->session()->flash('error', 'Kayıt Başarısız');
        }
        return redirect()->route('admin.footer-settings.index', ['lang_code' => $request->lang_code]);
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
        $footer = Footer::findOrFail($id);
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
                $imageManager = ImageManager::where('type', 'footer_resim_geniş')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($footer->title.'-l',$request->cropped_data,'logos',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is one.
                 */
                if (file_exists($footer->logo_large_screen)) {
                    unlink($footer->logo_large_screen);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $footer->logo_large_screen = $result;
                if ($footer->save()) {
                    $request->session()->flash('success', 'Footer Güncellendi');
                } else {
                    $request->session()->flash('error', 'Footer Güncellenemedi');
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
            if ($request->cropped_data_mobile) {
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                * Quality, fize size, image sizes.
                */
                $imageManager = ImageManager::where('type', 'footer_resim_mobil')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($footer->title.'-m',$request->cropped_data_mobile,'logos',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is one.
                 */
                if (file_exists($footer->logo_small_screen)) {
                    unlink($footer->logo_small_screen);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $footer->logo_small_screen = $result;
                if ($footer->save()) {
                    $request->session()->flash('success', 'Footer Güncellendi');
                } else {
                    $request->session()->flash('error', 'Footer Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_small');    //Which tab will be opened.
            return back();
        }else if($request->has('updatefootersettings')){

            $footer->title = $request->title;
            $footer->description = $request->description;
            $footer->copy_right = $request->copy_right;

            if ($footer->save()) {
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
