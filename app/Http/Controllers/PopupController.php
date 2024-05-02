<?php

namespace App\Http\Controllers;

use App\Models\Popup;
use App\Models\Language;
use App\Models\ImageManager;
use App\Services\FileManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class PopupController extends Controller
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
        if (0 == Popup::all()->count()) {
            return view('admin.popup-settings-add', ['lang_code' => session('lang_code'), 'langs' => Language::all()]); //Parameter came from nav link
        } else {
            $popup = Popup::where('lang_code', $request->lang_code)->get();
            if (0 == $popup->count()) {
                return view('admin.popup-settings-add', ['lang_code' => $request->lang_code, 'langs' => Language::all()]);
            }
            return view('admin.popup-settings-update', [
                'lang_code' => $request->lang_code,
                'content' => $popup[0],
                'langs' => Language::orderBy('order','asc')->get(),
                'large_size' => ImageManager::where('type','popup_resim_geniş')->first(),
                'mobile_size' => ImageManager::where('type','popup_resim_mobil')->first(),
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

        $popup = new Popup();
        $popup->title = $request->title;
        $popup->subtitle = $request->subtitle;
        $popup->description = $request->description;
        $popup->short_description = $request->short_description;
        $popup->video_url = $request->video_url;
        $popup->start_date = $request->start_date;
        $popup->end_date = $request->end_date;
        $popup->lang_code = $request->lang_code;
        if ('on' == $request->is_desktop_active) {
            $popup->is_desktop_active = 1;
        } else {
            $popup->is_desktop_active = 0;
        }

        if ('on' == $request->is_mobile_active) {
            $popup->is_mobile_active = 1;
        } else {
            $popup->is_mobile_active = 0;
        }

        if ($popup->save()) {
            $request->session()->flash('success', 'Kayıt Başarılı');
        } else {
            $request->session()->flash('error', 'Kayıt Başarısız');
        }
        return redirect()->route('admin.popup-settings.index', ['lang_code' => $request->lang_code]);
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
     * Unique dosya ismi üret
     */
    public function generateUniqueFileName($extension)
    {
        return uniqid() . '.' . $extension;
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
        $popup = Popup::findOrFail($id);
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Three different forms.
         */
        if($request->has('updateimagelarge')){
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Validation for file mime from FileManagerService.
             */
            if($request->hasFile('image_large_screen')){
                if(!$this->fileManagerService->checkExtension($request->image_large_screen)){
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
                $imageManager = ImageManager::where('type', 'popup_resim_geniş')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($popup->title.'-l', $request->cropped_data, 'images', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is one.
                 */
                if (file_exists($popup->image_large_screen)) {
                    unlink($popup->image_large_screen);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $popup->image_large_screen = $result;
                if ($popup->save()) {
                    $request->session()->flash('success', 'Popup Güncellendi');
                } else {
                    $request->session()->flash('error', 'Popup Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_large');    //Which tab will be opened.
            return back();
        }else if($request->has('updateimagesmall')){
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Validation for file mime from FileManagerService.
             */
            if($request->hasFile('image_small_screen')){
                if(!$this->fileManagerService->checkExtension($request->image_small_screen)){
                    $request->session()->flash('file_extension_error','Dosya uzantısı.');
                    return back();
                }
            }

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Upload cropped image if there is one.
             */
            if ($request->cropped_data_mobile) {
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                * Quality, fize size, image sizes.
                */
                $imageManager = ImageManager::where('type', 'popup_resim_mobil')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($popup->title.'-m', $request->cropped_data_mobile, 'images', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is one.
                 */
                if (file_exists($popup->image_small_screen)) {
                    unlink($popup->image_small_screen);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $popup->image_small_screen = $result;
                if ($popup->save()) {
                    $request->session()->flash('success', 'Popup Güncellendi');
                } else {
                    $request->session()->flash('error', 'Popup Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_small');    //Which tab will be opened.
            return back();
        }else if($request->has('updatepopupsettings')){
            $popup->title = $request->title;
            $popup->subtitle = $request->subtitle;
            $popup->description = $request->description;
            $popup->short_description = $request->short_description;
            $popup->video_url = $request->video_url;
            $popup->start_date = $request->start_date;
            $popup->end_date = $request->end_date;
            if ('on' == $request->is_desktop_active) {
                $popup->is_desktop_active = 1;
            } else {
                $popup->is_desktop_active = 0;
            }

            if ('on' == $request->is_mobile_active) {
                $popup->is_mobile_active = 1;
            } else {
                $popup->is_mobile_active = 0;
            }

            if ($popup->save()) {
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
