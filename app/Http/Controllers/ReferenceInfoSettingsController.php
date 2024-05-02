<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Menu;
use App\Models\ReferenceInfo;
use App\Models\ImageManager;
use App\Services\FileManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class ReferenceInfoSettingsController extends Controller
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
         * if not null and there is content for that language, reroutes to edit page with lang_code, reference, langs, image sizes parameters.
         */
        if (0 == ReferenceInfo::all()->count()) {
            return view('admin.reference-info-settings-add', ['lang_code' => session('lang_code'), 'langs' => Language::all()]); //Parameter comes from nav manu
        } else {
            $referenceInfo = ReferenceInfo::where('lang_code', $request->lang_code)->get();
            if (0 == $referenceInfo->count()) {
                return view('admin.reference-info-settings-add', ['lang_code' => $request->lang_code, 'langs' => Language::all()]);
            }
            return view('admin.reference-info-settings-update', [
                'lang_code' => $request->lang_code,
                'reference' => $referenceInfo[0],
                'langs' => Language::orderBy('order','asc')->get(),
                'page' => $request->page,
                'large_size' => ImageManager::where('type','referans_içerik_resim_geniş')->first(),
                'mobile_size' => ImageManager::where('type','referans_içerik_resim_mobil')->first(),
                'banner_size' => ImageManager::where('type','referans_içerik_banner')->first()
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
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

        return view('admin.reference-info-settings-add', ['lang_code' => $request->lang_code]);
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

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * UID, uses for matching content and menus.
         */
        $uniqueid = sha1(uniqid());

        $reference = new ReferenceInfo();
        $reference->title = $request->title;
        $reference->subtitle = $request->subtitle;
        $reference->description = $request->description;
        $reference->short_description = $request->short_description;
        $reference->keywords = $request->keywords;
        $reference->menu_name = $request->menu_name;
        $reference->video_embed_code = $request->video_embed_code;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * On same content but different languages has same "menu_code" for matching.
         * UID for just one menu and one content.
         */
        $reference->uid = $uniqueid;
        $reference->lang_code = $request->lang_code;
        $reference->menu_code = 'references';

        /** ►►►►► DEVELOPER ◄◄◄◄◄
        * SLUG generating.
         */
        $reference->slug = Str::slug($request->title);

        /** ►►►►► DEVELOPER ◄◄◄◄◄
        * Save data and if it is success,
        * Create a menu with same data;
        * Same UID, menu_name, lang_code, menu_code,
         */
        if ($reference->save()) {
            $menu = new Menu();
            $menu->uid = $uniqueid;
            $menu->order = (Menu::where('lang_code', $request->lang_code)->get()->count()) + 1;
            $menu->menu_name = $request->menu_name;
            $menu->lang_code = $request->lang_code;
            $menu->menu_code = 'references';
            $menu->content_slug = $reference->slug;
            $menu->content_id = $reference->id;
            $menu->save();

            $request->session()->flash('success', 'Başarıyla oluşturuldu');
            return redirect()->route('admin.reference-info-settings.index', ['lang_code' => $request->lang_code])->withInput();
        } else {
            $request->session()->flash('error', 'Kaydetme Başarısız');
            return redirect()->route('admin.reference-info-settings.add', ['lang_code' => $request->lang_code])->withInput();
        }
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
        $reference = ReferenceInfo::findOrFail($id);
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Four different forms.
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
                $imageManager = ImageManager::where('type', 'referans_içerik_resim_geniş')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($reference->title.'-l', $request->cropped_data, 'images', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is one.
                 */
                if (file_exists($reference->image_large_screen)) {
                    unlink($reference->image_large_screen);
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $reference->image_large_screen = $result;
                if ($reference->save()) {
                    $request->session()->flash('success', 'Hakkımızda Güncellendi');
                } else {
                    $request->session()->flash('error', 'Hakkımızda Güncellenemedi');
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
                $imageManager = ImageManager::where('type', 'referans_içerik_resim_mobil')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($reference->title.'-m', $request->cropped_data_mobile, 'images', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is one.
                 */
                if (file_exists($reference->image_small_screen)) {
                    unlink($reference->image_small_screen);
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $reference->image_small_screen = $result;
                if ($reference->save()) {
                    $request->session()->flash('success', 'Hakkımızda Güncellendi');
                } else {
                    $request->session()->flash('error', 'Hakkımızda Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_small');    //Which tab will be opened.
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
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Upload cropped image if there is one.
             */
            if ($request->cropped_data_banner) {
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                * Quality, fize size, image sizes.
                */
                $imageManager = ImageManager::where('type', 'referans_içerik_banner')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($reference->title.'-b', $request->cropped_data_banner, 'hero', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is one.
                 */
                if (file_exists($reference->hero)) {
                    unlink($reference->hero);
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $reference->hero = $result;
                if ($reference->save()) {
                    $request->session()->flash('success', 'Hakkımızda Güncellendi');
                } else {
                    $request->session()->flash('error', 'Hakkımızda Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_banner');    //Which tab will be opened.
            return back();
        }else if($request->has('updatereferenceinfo')){

            $reference->title = $request->title;
            $reference->subtitle = $request->subtitle;
            $reference->description = $request->description;
            $reference->short_description = $request->short_description;
            $reference->keywords = $request->keywords;
            $reference->video_embed_code = $request->video_embed_code;
            $reference->menu_name = $request->menu_name;

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Updating slug
             */
            $reference->slug = Str::slug($request->title);

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Switch tool returns 'on' or null.
             */
            if ('on' == $request->is_desktop_active) {
                $reference->is_desktop_active = 1;
            } else {
                $reference->is_desktop_active = 0;
            }
            if ('on' == $request->is_mobile_active) {
                $reference->is_mobile_active = 1;
            } else {
                $reference->is_mobile_active = 0;
            }

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Save it and update the menu that has same UID.
             */
            if ($reference->save()) {
                $menu = Menu::where('uid', $reference->uid)->get();
                $menu[0]->menu_name = $request->menu_name;
                $menu[0]->content_slug = $reference->slug;

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * If the content is passive than menu will be passive too but if menu got passive, content will not.
                 */
                if (0 == $reference->is_desktop_active) {
                    $menu[0]->is_desktop_active = 0;
                }else{
                    $menu[0]->is_desktop_active = 1;
                }
                if (0 == $reference->is_mobile_active) {
                    $menu[0]->is_mobile_active = 0;
                }else{
                    $menu[0]->is_mobile_active = 1;
                }
                $menu[0]->save();

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
