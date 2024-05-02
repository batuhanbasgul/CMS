<?php

namespace App\Http\Controllers;

use App\Models\ImageManager;
use App\Models\AnnouncementInfo;
use App\Models\Language;
use App\Models\Menu;
use App\Services\FileManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class AnnouncementInfoSettingsController extends Controller
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
         * if not null and there is content for that language, reroutes to edit page with lang_code, announcementInfo, langs, image sizes parameters.
         */
        if (0 == AnnouncementInfo::all()->count()) {
            return view('admin.announcement-info-settings-add', ['lang_code' => session('lang_code'), 'langs' => Language::all()]); //Parameter comes from nav manu
        } else {
            $announcementInfo = AnnouncementInfo::where('lang_code', $request->lang_code)->get();
            if (0 == $announcementInfo->count()) {
                return view('admin.announcement-info-settings-add', ['lang_code' => $request->lang_code, 'langs' => Language::all()]);
            }
            return view('admin.announcement-info-settings-update', [
                'lang_code' => $request->lang_code,
                'announcement' => $announcementInfo[0],
                'langs' => Language::orderBy('order','asc')->get(),
                'large_size' => ImageManager::where('type','duyuru_içerik_resim_geniş')->first(),
                'mobile_size' => ImageManager::where('type','duyuru_içerik_resim_mobil')->first(),
                'banner_size' => ImageManager::where('type','duyuru_içerik_banner')->first(),
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

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * UID, uses for matching content and menus.
         */
        $uniqueid = sha1(uniqid());

        $announcement = new AnnouncementInfo();
        $announcement->title = $request->title;
        $announcement->subtitle = $request->subtitle;
        $announcement->author = $request->author;
        $announcement->description = $request->description;
        $announcement->short_description = $request->short_description;
        $announcement->keywords = $request->keywords;
        $announcement->video_embed_code = $request->video_embed_code;
        $announcement->menu_name = $request->menu_name;
        $announcement->announcement_date = $request->announcement_date;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Page limit to show on website.
         */
        if($request->page_limit){
            $announcement->page_limit = $request->page_limit;
        }else{
            $announcement->page_limit = 3;
        }

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * On same content but different languages has same "menu_code" for matching.
         * UID for just one menu and one content.
         */
        $announcement->uid = $uniqueid;
        $announcement->menu_code = 'announcement';
        $announcement->lang_code = $request->lang_code;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
        * SLUG generating.
         */
        $announcement->slug = Str::slug($request->title);

        /** ►►►►► DEVELOPER ◄◄◄◄◄
        * Save data and if it is success,
        * Create a menu with same data;
        * Same UID, menu_name, lang_code, menu_code,
         */
        if ($announcement->save()) {
            $menu = new Menu();
            $menu->uid = $uniqueid;
            $menu->order = (Menu::where('lang_code', $request->lang_code)->get()->count()) + 1;
            $menu->menu_name = $request->menu_name;
            $menu->lang_code = $request->lang_code;
            $menu->menu_code = 'announcement';
            $menu->content_slug = $announcement->slug;
            $menu->content_id = $announcement->id;
            $menu->save();
            $request->session()->flash('success', 'Başarıla oluşturuldu');
            return redirect()->route('admin.announcement-info-settings.index', ['lang_code' => $request->lang_code])->withInput();
        } else {
            $request->session()->flash('error', 'Kaydetme Başarısız');
            return redirect()->route('admin.announcement-info-settings.index', ['lang_code' => $request->lang_code])->withInput();
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
        $announcement = AnnouncementInfo::findOrFail($id);
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Four different forms.
         */
        if ($request->has('updateimagelarge')) {
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
                $imageManager = ImageManager::where('type', 'duyuru_içerik_resim_geniş')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($announcement->title.'-l', $request->cropped_data, 'images', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is one.
                 */
                if (file_exists($announcement->image_large_screen)) {
                    unlink($announcement->image_large_screen);
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $announcement->image_large_screen = $result;
                if ($announcement->save()) {
                    $request->session()->flash('success', 'Duyurular Sayfası Güncellendi');
                } else {
                    $request->session()->flash('error', 'Duyurular Sayfası Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_large');    //Which tab will be opened.
            return back();
        } else if ($request->has('updateimagesmall')) {
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
                $imageManager = ImageManager::where('type', 'duyuru_içerik_resim_mobil')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($announcement->title.'-m',$request->cropped_data_mobile,'images',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is one.
                 */
                if (file_exists($announcement->image_small_screen)) {
                    unlink($announcement->image_small_screen);
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $announcement->image_small_screen = $result;
                if ($announcement->save()) {
                    $request->session()->flash('success', 'Duyurular Sayfası Güncellendi');
                } else {
                    $request->session()->flash('error', 'Duyurular Sayfası Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_small');    //Which tab will be opened.
            return back();
        } else if ($request->has('updateimagebanner')) {
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
                $imageManager = ImageManager::where('type', 'duyuru_içerik_banner')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($announcement->title.'-b',$request->cropped_data_banner,'hero',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is one.
                 */
                if (file_exists($announcement->hero)) {
                    unlink($announcement->hero);
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $announcement->hero = $result;
                if ($announcement->save()) {
                    $request->session()->flash('success', 'Duyurular Sayfası Güncellendi');
                } else {
                    $request->session()->flash('error', 'Duyurular Sayfası Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_banner');    //Which tab will be opened.
            return back();
        } else if ($request->has('updateannouncementinfo')) {

            $announcement->title = $request->title;
            $announcement->subtitle = $request->subtitle;
            $announcement->description = $request->description;
            $announcement->author = $request->author;
            $announcement->short_description = $request->short_description;
            $announcement->keywords = $request->keywords;
            $announcement->video_embed_code = $request->video_embed_code;
            $announcement->menu_name = $request->menu_name;
            $announcement->announcement_date = $request->announcement_date;

            if($request->page_limit){
                $announcement->page_limit = $request->page_limit;
            }else{
                $announcement->page_limit = 3;
            }

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Updating slug
             */
            $announcement->slug = Str::slug($request->title);

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Switch tool returns 'on' or null.
             */
            if ('on' == $request->is_desktop_active) {
                $announcement->is_desktop_active = 1;
            } else {
                $announcement->is_desktop_active = 0;
            }
            if ('on' == $request->is_mobile_active) {
                $announcement->is_mobile_active = 1;
            } else {
                $announcement->is_mobile_active = 0;
            }

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Save it and update the menu that has same UID.
             */
            if ($announcement->save()) {
                $menu = Menu::where('uid', $announcement->uid)->get();
                $menu[0]->menu_name = $request->menu_name;
                $menu[0]->content_slug = $announcement->slug;

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * If the content is passive than menu will be passive too but if menu got passive, content will not.
                 */
                if (0 == $announcement->is_desktop_active) {
                    $menu[0]->is_desktop_active = 0;
                }else{
                    $menu[0]->is_desktop_active = 1;
                }
                if (0 == $announcement->is_mobile_active) {
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
