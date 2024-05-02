<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageManager;
use App\Models\Page;
use App\Models\Language;
use App\Models\PageImage;
use App\Models\Menu;
use App\Services\FileManagerService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class PageController extends Controller
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
        if($request->refresh){
            return back();
        }
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
         * if not null and no content on that language reroutes to "add" page.
         * if not null and there is content for that language, reroutes to edit page with lang_code, pages, langs parameters.
         */
        if ($request->lang_code) {
            return view('admin.page-settings', [
                'langs' => Language::orderBy('order','asc')->get(),
                'pages' => Page::where('lang_code', $request->lang_code)->get(),
                'lang_code' => $request->lang_code]);
        } else {
            return view('admin.page-settings', [
                'langs' => Language::orderBy('order','asc')->get(),
                'pages' => Page::where('lang_code', session('lang_code'))->get(),
                'lang_code' => session('lang_code'),
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

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Görüntülemedeki dil kodu ile birlikte oluşturma sayfasına yönlendirilir,
         * O dil kodu ile oluşturulur.
         */
        return view('admin.page-settings-add', ['lang_code' => $request->lang_code, 'langs' => Language::all()]);
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

        $page = new Page();
        $page->title = $request->title;
        $page->subtitle = $request->subtitle;
        $page->author = $request->author;
        $page->short_description = $request->short_description;
        $page->page_date = $request->page_date;
        $page->description = $request->description;
        $page->keywords = $request->keywords;
        $page->video_embed_code = $request->video_embed_code;
        $page->menu_name = $request->menu_name;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * On same content but different languages has same "menu_code" for matching.
         * UID for just one menu and one content.
         */
        $page->uid = $uniqueid;
        $page->menu_code = 'page';
        $page->lang_code = $request->lang_code;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * SLUG generating with ID.
         */
        $page->slug = 'temp';
        $page->save();
        $page->slug = Str::slug($request->title.'-'.$page->id);

        if ($page->save()) {
            $menu = new Menu();
            $menu->uid = $uniqueid;
            $menu->order = (Menu::where('lang_code', $request->lang_code)->get()->count()) + 1;
            $menu->menu_name = $request->menu_name;
            $menu->lang_code = $request->lang_code;
            $menu->menu_code = 'page';
            $menu->content_slug = $page->slug;
            $menu->content_id = $page->id;
            $menu->save();
            $request->session()->flash('success', 'Başarıyla oluşturuldu');
            return redirect()->route('admin.page-settings.edit', [$page->id,'lang_code' => $request->lang_code]);
        } else {
            $request->session()->flash('error', 'Kaydetme Başarısız');
            return redirect()->route('admin.page-settings.edit', [$page->id,'lang_code' => $request->lang_code]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
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
         * Selected Page - $page
         * All Pages on that language - $pages
         * Selected Page's images. - $images
         */
        $page = Page::findOrFail($id);
        $pages = Page::where('lang_code', $page->lang_code)->get();
        $images = PageImage::where('page_id', $page->id)->orderBy('order', 'asc')->get();
        return view('admin.page-settings-update', [
            'page' => $page,
            'pages' => $pages,
            'langs' => Language::all(),
            'lang_code' => $request->lang_code,
            'images' => $images,
            'page_number' => $request->page_number,
            'lang_code' => $request->lang_code,
            'large_size' => ImageManager::where('type','sayfa_resim_geniş')->first(),
            'mobile_size' => ImageManager::where('type','sayfa_resim_mobil')->first(),
            'banner_size' => ImageManager::where('type','sayfa_banner')->first(),
            'image_size' => ImageManager::where('type','sayfa_resim')->first(),
        ]);
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
        $page = Page::findOrFail($id);

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Five different forms.
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
                $imageManager = ImageManager::where('type', 'sayfa_resim_geniş')->first();
                $imageManagerThumbnail = ImageManager::where('type','sayfa_thumbnail_geniş')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($page->title.'-l',$request->cropped_data,'images',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult = $this->fileManagerService->uploadImage($page->title.'-l',$request->cropped_data,'images/thumbnail',$imageManagerThumbnail);
                if($thumbnailResult == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old files if there is.
                 */
                if (file_exists($page->image_large_screen)) {
                    unlink($page->image_large_screen);
                }
                if (file_exists($page->thumbnail_large_screen)) {
                    unlink($page->thumbnail_large_screen);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $page->image_large_screen = $result;
                $page->thumbnail_large_screen = $thumbnailResult;
                if ($page->save()) {
                    $request->session()->flash('success', 'Sayfa Güncellendi');
                } else {
                    $request->session()->flash('error', 'Sayfa Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_large');                 //Which tab will be opened.
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
                $imageManager = ImageManager::where('type', 'sayfa_resim_mobil')->first();
                $imageManagerThumbnail = ImageManager::where('type','sayfa_thumbnail_mobil')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($page->title.'-m',$request->cropped_data_mobile,'images',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult = $this->fileManagerService->uploadImage($page->title.'-m',$request->cropped_data_mobile,'images/thumbnail',$imageManagerThumbnail);
                if($thumbnailResult == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old files if there is.
                 */
                if (file_exists($page->image_small_screen)) {
                    unlink($page->image_small_screen);
                }
                if (file_exists($page->thumbnail_small_screen)) {
                    unlink($page->thumbnail_small_screen);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $page->image_small_screen = $result;
                $page->thumbnail_small_screen = $thumbnailResult;
                if ($page->save()) {
                    $request->session()->flash('success', 'Sayfa Güncellendi');
                } else {
                    $request->session()->flash('error', 'Sayfa Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_small');                 //Which tab will be opened.
            return back();
        }else if($request->has('updateimagemulti')){
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Validation for file mime from FileManagerService.
             */
            if($request->hasFile('image')){
                if(!$this->fileManagerService->checkExtension($request->image)){
                    $request->session()->flash('file_extension_error','Dosya uzantısı.');
                    return back();
                }
            }
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Upload cropped image if there is one.
             */
            if ($request->cropped_data_multi) {
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                * Quality, fize size, image sizes.
                */
                $imageManager = ImageManager::where('type', 'sayfa_resim')->first();
                $imageManagerThumbnail = ImageManager::where('type','sayfa_thumbnail_geniş')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($page->title,$request->cropped_data_multi,'pages',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult = $this->fileManagerService->uploadImage($page->title,$request->cropped_data_multi,'pages/thumbnail',$imageManagerThumbnail);
                if($thumbnailResult == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Announcement images are in different table on database.
                 * Adding on last order.
                 * UID is same on different languages for same images. But saves them their ids to changing them independently. (Order and auto generating)
                 */
                if (PageImage::where('page_id', $page->id)->orderBy('id', 'desc')->get()->count() > 0) {    //bu id olan kayıt varsa
                    $order = PageImage::where('page_id', $page->id)->orderBy('order', 'desc')->first()->order;
                } else {
                    $order = 0;
                }
                $order += 1;

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving page image's file path.
                 */
                $pageImage = new PageImage();
                $pageImage->image = $result;
                $pageImage->thumbnail = $thumbnailResult;
                $pageImage->order = $order;
                $pageImage->page_id = $page->id;
                $pageImage->save();

                if ($page->save()) {
                    $request->session()->flash('success', 'Sayfa Güncellendi');
                } else {
                    $request->session()->flash('error', 'Sayfa Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_multi');                 //Which tab will be opened.
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
                $imageManager = ImageManager::where('type', 'sayfa_banner')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($page->title.'-b',$request->cropped_data_banner,'hero',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is.
                 */
                if (file_exists($page->hero)) {
                    unlink($page->hero);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $page->hero = $result;
                if ($page->save()) {
                    $request->session()->flash('success', 'Sayfa Güncellendi');
                } else {
                    $request->session()->flash('error', 'Sayfa Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_banner');                 //Which tab will be opened.
            return back();
        }else if($request->has('updatepage')){

            $page->title = $request->title;
            $page->subtitle = $request->subtitle;
            $page->author = $request->author;
            $page->short_description = $request->short_description;
            $page->page_date = $request->page_date;
            $page->description = $request->description;
            $page->keywords = $request->keywords;
            $page->video_embed_code = $request->video_embed_code;
            $page->menu_name = $request->menu_name;

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Updating slug with old slug number.
             */
            $page->slug = Str::slug($request->title.'-'.last(explode('-',$page->slug)));

            if ('on' == $request->is_desktop_active) {
                $page->is_desktop_active = 1;
            } else {
                $page->is_desktop_active = 0;
            }
            if ('on' == $request->is_mobile_active) {
                $page->is_mobile_active = 1;
            } else {
                $page->is_mobile_active = 0;
            }

            if ($page->save()) {
                $menu = Menu::where('uid', $page->uid)->get();
                $menu[0]->menu_name = $request->menu_name;
                $menu[0]->content_slug = $page->slug;

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * If the content will be passive menu will be too but if menu will be passive content is not.
                 */
                if (0 == $page->is_desktop_active) {
                    $menu[0]->is_desktop_active = 0;
                }else{
                    $menu[0]->is_desktop_active = 1;
                }
                if (0 == $page->is_mobile_active) {
                    $menu[0]->is_mobile_active = 0;
                }else{
                    $menu[0]->is_mobile_active = 1;
                }
                $menu[0]->save();
                $request->session()->flash('success', 'Başarıyla Güncellendi');
            } else {
                $request->session()->flash('error', 'Güncelleme Başarısız');
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
        $page = Page::findOrFail($id);
        $lang_code = $page->lang_code;
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Delete other images of that page.
         */
        foreach(PageImage::where('page_id',$page->id)->get() as $image){
            if(file_exists($image->image)){
                unlink($image->image);
            }
            $image->delete();
        }
        $menu = Menu::where('uid', $page->uid)->where('lang_code',$page->lang_code)->get();
        $menu[0]->delete();
        $page->delete();
        session()->flash('success', 'Deleted');
        return redirect()->route('admin.page-settings.index', ['lang_code' => $lang_code]);
    }
}
