<?php

namespace App\Http\Controllers;

use App\Models\AppSettings;
use App\Models\Language;
use App\Models\ImageManager;
use App\Services\FileManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class AppSettingsController extends Controller
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
         * if not null and there is content for that language, routes to edit page with lang_code, settings, langs, image size parameters.
         */
        if (0 == AppSettings::all()->count()) {
            return view('admin.app-settings-add', ['lang_code' => session('lang_code'), 'langs' => Language::all()]); //Parameter came from nav link
        } else {
            $appSettings = AppSettings::where('lang_code', $request->lang_code)->get();
            if (0 == $appSettings->count()) {
                return view('admin.app-settings-add', ['lang_code' => $request->lang_code, 'langs' => Language::all()]);
            }
            return view('admin.app-settings-update', [
                'lang_code' => $request->lang_code,
                'settings' => $appSettings[0],
                'langs' => Language::orderBy('order','asc')->get(),
                'favicon_size' => ImageManager::where('type','website_favicon')->first(),
                'banner_size' => ImageManager::where('type','website_banner')->first(),
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

        $settings = new AppSettings();
        $settings->title = $request->title;
        $settings->keywords = $request->keywords;
        $settings->description = $request->app_description;
        $settings->google_analytic = $request->google_analytic;
        $settings->yandex_verification_code = $request->yandex_verification_code;
        $settings->lang_code = $request->lang_code;

        if ($settings->save()) {
            $request->session()->flash('success', 'Başarıyla Kaydedildi');
        } else {
            $request->session()->flash('error', 'Kaydetme Başarısız');
        }
        return redirect()->route('admin.app-settings.index',['lang_code' => $request->lang_code])->withInput();
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
        $settings = AppSettings::findOrFail($id);
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Three different forms.
         */
        if($request->has('updateimagefavicon')){
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Validation for file mime from FileManagerService.
             */
            if($request->hasFile('fav_icon')){
                if(!$this->fileManagerService->checkExtension($request->fav_icon)){
                    $request->session()->flash('file_extension_error','Dosya uzantısı.');
                    return back();
                }
            }
            if ($request->cropped_data_favicon) {

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Upload cropped image if there is one.
                 */
                $imageManager = ImageManager::where('type', 'website_favicon')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                * Quality, fize size, image sizes.
                */
                $result = $this->fileManagerService->uploadImage($settings->title,$request->cropped_data_favicon,'icons',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is one.
                 */
                if (file_exists($settings->fav_icon)) {
                    unlink($settings->fav_icon);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $settings->fav_icon = $result;
                session()->put('fav-icon', $settings->fav_icon);    //Session for to show it on header instantly.
                session()->flash('tab_page','fav_icon');    //Which tab will be opened.
                if ($settings->save()) {
                    $request->session()->flash('success', 'Fav Icon Güncellendi');
                } else {
                    $request->session()->flash('error', 'Fav Icon Güncellenemedi');
                }
            }
            return back();
        }else if($request->has('updateimagebanner')){
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Validation for file mime from FileManagerService.
             */
            if($request->hasFile('hero')){
                if(!$this->fileManagerService->checkExtension($request->hero)){
                    $request->session()->flash('file_extension_error','Dosya uzantısı.');
                    return back();
                }
            }
            if ($request->cropped_data_banner) {

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Upload cropped image if there is one.
                 */
                $imageManager = ImageManager::where('type', 'website_banner')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                * Quality, fize size, image sizes.
                */
                $result = $this->fileManagerService->uploadImage($settings->title.'-b',$request->cropped_data_banner,'hero',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is one.
                 */
                if (file_exists($settings->hero)) {
                    unlink($settings->hero);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                session()->flash('tab_page','image_banner');    //Which tab will be opened.
                $settings->hero = $result;
                if ($settings->save()) {
                    $request->session()->flash('success', 'Hero Güncellendi');
                } else {
                    $request->session()->flash('error', 'Hero Güncellenemedi');
                }
            }
            return back();
        }else if($request->has('updateappsettings')){

            $settings->title = $request->title;
            $settings->keywords = $request->keywords;
            $settings->description = $request->app_description;
            $settings->google_analytic = $request->google_analytic;
            $settings->yandex_verification_code = $request->yandex_verification_code;
            if ($settings->save()) {
                $request->session()->flash('success', 'Güncellendi');
            } else {
                $request->session()->flash('error', 'Güncellenmedi');
            }
            session()->flash('tab_page','context');    //Which tab will be opened.
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
