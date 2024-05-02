<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageManager;
use App\Models\Announcement;
use App\Models\AnnouncementImage;
use App\Models\Language;
use App\Services\FileManagerService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class AnnouncementSettingsController extends Controller
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
         * if not null and there is content for that language, reroutes to edit page with lang_code, announcements, langs parameters.
         */
        if ($request->lang_code) {
            return view('admin.announcement-settings', ['langs' => Language::orderBy('order','asc')->get(), 'announcements' => Announcement::where('lang_code', $request->lang_code)->get(), 'lang_code' => $request->lang_code]);
        } else {
            return view('admin.announcement-settings', [
                'langs' => Language::orderBy('order','asc')->get(),
                'announcements' => Announcement::where('lang_code', session('lang_code'))->get(),
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
         * Routes to creating page with lang code. And uses that lang code to create.
         */
        return view('admin.announcement-settings-add', ['lang_code' => $request->lang_code, 'langs' => Language::all()]);
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

        $announcement = new Announcement();
        $announcement->title = $request->title;
        $announcement->subtitle = $request->subtitle;
        $announcement->author = $request->author;
        $announcement->short_description = $request->short_description;
        $announcement->announcement_date = $request->announcement_date;
        $announcement->description = $request->description;
        $announcement->keywords = $request->keywords;
        $announcement->video_embed_code = $request->video_embed_code;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * On same content but different languages has same "menu_code" for matching.
         * UID for just one menu and one content.
         */
        $announcement->uid = $uniqueid;
        $announcement->lang_code = $request->lang_code;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Add to last order.
         */
        $a_order = 0;
        foreach (Announcement::where('lang_code', $request->lang_code)->get() as $a) {
            if ($a->order > $a_order) {
                $a_order = $a->order;
            }
        }
        $announcement->order = $a_order + 1;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * SLUG generating with ID.
         */
        $announcement->slug = 'temp';
        $announcement->save();
        $announcement->slug = Str::slug($request->title.'-'.$announcement->id);

        if ($announcement->save()) {

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * If there is other languages , create automatic data with images.
             */
            foreach (Language::all() as $lang) {
                if ($request->lang_code != $lang->lang_code) {
                    $announcement = new Announcement();
                    $announcement->title = '-';
                    $announcement->subtitle = '-';
                    $announcement->author = $request->author;
                    $announcement->short_description = '-';
                    $announcement->announcement_date = $request->announcement_date;
                    $announcement->keywords = '-';
                    $announcement->description = '-';
                    $announcement->video_embed_code = $request->video_embed_code;
                    $announcement->order = $a_order + 1;
                    $announcement->lang_code = $lang->lang_code;
                    $announcement->uid = $uniqueid;

                    /** ►►►►► DEVELOPER ◄◄◄◄◄
                     * SLUG generating with ID.
                     */
                    $announcement->slug = 'temp';
                    $announcement->save();
                    $announcement->slug = Str::slug(uniqid().'-'.$announcement->id);
                    $announcement->save();
                }
            }
            $request->session()->flash('success', 'Başarıyla oluşturuldu');
            return redirect()->route('admin.announcement-settings.index', ['lang_code' => $request->lang_code]);
        } else {
            $request->session()->flash('error', 'Kaydetme Başarısız');
            return redirect()->route('admin.announcement-settings.index', ['lang_code' => $request->lang_code]);
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
         * Selected Announcement - $announcement
         * All Announcements on that language - $announcements
         * Selected Announcement's images. - $images
         */
        $announcement = Announcement::findOrFail($id);
        $announcements = Announcement::where('lang_code', $announcement->lang_code)->orderBy('order', 'asc')->get();
        $images = AnnouncementImage::where('announcement_id', $announcement->id)->orderBy('order', 'asc')->get();
        return view('admin.announcement-settings-update', [
            'announcement' => $announcement,
            'announcements' => $announcements,
            'images' => $images,
            'lang_code' => $announcement->lang_code,
            'langs' => Language::all(),
            'large_size' => ImageManager::where('type','duyuru_resim_geniş')->first(),
            'mobile_size' => ImageManager::where('type','duyuru_resim_mobil')->first(),
            'banner_size' => ImageManager::where('type','duyuru_banner')->first(),
            'image_size' => ImageManager::where('type','duyuru_resim')->first(),
        ]);
    }

    /** ►►►►► DEVELOPER ◄◄◄◄◄
     * Order management page.
     */
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editOrder(Request $request)
    {
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Check if there is announcement on that language.
         */
        $announcements = Announcement::where('lang_code', $request->lang_code)->orderBy('order','asc')->get();
        if(count($announcements) == 0){
            $request->session()->flash('no_announcement','Duyuru Kaydı Bulunamadı');
            return back();
        }
        return view('admin.announcement-settings-update-order', [
            'announcements' => $announcements,
            'langs' => Language::all(),
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
        $announcement = Announcement::findOrFail($id);

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Six different forms.
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
                $imageManager = ImageManager::where('type', 'duyuru_resim_geniş')->first();
                $imageManagerThumbnail = ImageManager::where('type','duyuru_thumbnail_geniş')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($announcement->title.'-l',$request->cropped_data,'images',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult = $this->fileManagerService->uploadImage($announcement->title.'-l',$request->cropped_data,'images/thumbnail',$imageManagerThumbnail);
                if($thumbnailResult == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old files if there is.
                 */
                if (file_exists($announcement->image_large_screen)) {
                    unlink($announcement->image_large_screen);
                }
                if (file_exists($announcement->thumbnail_large_screen)) {
                    unlink($announcement->thumbnail_large_screen);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $announcement->image_large_screen = $result;
                $announcement->thumbnail_large_screen = $thumbnailResult;
                if ($announcement->save()) {
                    $request->session()->flash('success', 'Ürün Güncellendi');
                } else {
                    $request->session()->flash('error', 'Ürün Güncellenemedi');
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
                $imageManager = ImageManager::where('type', 'duyuru_resim_mobil')->first();
                $imageManagerThumbnail = ImageManager::where('type','duyuru_thumbnail_mobil')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($announcement->title.'-m',$request->cropped_data_mobile,'images',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult = $this->fileManagerService->uploadImage($announcement->title.'-m',$request->cropped_data_mobile,'images/thumbnail',$imageManagerThumbnail);
                if($thumbnailResult == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old files if there is.
                 */
                if (file_exists($announcement->image_small_screen)) {
                    unlink($announcement->image_small_screen);
                }
                if (file_exists($announcement->thumbnail_small_screen)) {
                    unlink($announcement->thumbnail_small_screen);
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $announcement->image_small_screen = $result;
                $announcement->thumbnail_small_screen = $thumbnailResult;
                if ($announcement->save()) {
                    $request->session()->flash('success', 'Ürün Güncellendi');
                } else {
                    $request->session()->flash('error', 'Ürün Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_small');                 //Which tab will be opened.
            return back();
        }else if($request->has('updateimagemulti')){
            /**
             * It is actually adding image to that announcement's gallery. Editing is on AnnouncementImageController.
             */
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
                $imageManager = ImageManager::where('type', 'duyuru_resim')->first();
                $imageManagerThumbnail = ImageManager::where('type','duyuru_thumbnail_geniş')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($announcement->title,$request->cropped_data_multi,'announcements',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult = $this->fileManagerService->uploadImage($announcement->title,$request->cropped_data_multi,'announcements/thumbnail',$imageManagerThumbnail);
                if($thumbnailResult == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Announcement images are in different table on database.
                 * Adding on last order.
                 * UID is same on different languages for same images. But saves them their ids to changing them independently. (Order and auto generating)
                 */
                if (AnnouncementImage::where('announcement_id', $announcement->id)->orderBy('id', 'desc')->get()->count() > 0) {
                    /** ►►►►► DEVELOPER ◄◄◄◄◄
                     * Last image order on that category.
                     * Checking every time, categories can be different.
                     */
                    $order = AnnouncementImage::where('announcement_id', $announcement->id)->orderBy('order', 'desc')->first()->order;
                } else {
                    $order = 0;
                }
                $order += 1;

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                foreach (Announcement::where('uid', $announcement->uid)->get() as $a) {
                    $announcementImage = new AnnouncementImage();
                    $announcementImage->image = $result;
                    $announcementImage->thumbnail = $thumbnailResult;
                    $announcementImage->order = $order;
                    $announcementImage->announcement_id = $a->id;
                    $announcementImage->save();
                }
                if ($announcement->save()) {
                    $request->session()->flash('success', 'Ürün Güncellendi');
                } else {
                    $request->session()->flash('error', 'Ürün Güncellenemedi');
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
                $imageManager = ImageManager::where('type', 'duyuru_banner')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($announcement->title.'-b',$request->cropped_data_banner,'hero',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old files if there is.
                 */
                if (file_exists($announcement->hero)) {
                    unlink($announcement->hero);
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $announcement->hero = $result;
                if ($announcement->save()) {
                    $request->session()->flash('success', 'Duyurular Güncellendi');
                } else {
                    $request->session()->flash('error', 'Duyurular Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_banner');                 //Which tab will be opened.
            return back();
        }else if($request->has('updateannouncementsettings')){

            $announcement->title = $request->title;
            $announcement->subtitle = $request->subtitle;
            $announcement->author = $request->author;
            $announcement->keywords = $request->keywords;
            $announcement->short_description = $request->short_description;
            $announcement->announcement_date = $request->announcement_date;
            $announcement->description = $request->description;
            $announcement->video_embed_code = $request->video_embed_code;

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Updating slug with old slug number.
             */
            $announcement->slug = Str::slug($request->title.'-'.last(explode('-',$announcement->slug)));

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Switch tool returns 'on' or null.
             */
            if ('on' == $request->is_active) {
                $announcement->is_active = 1;
            } else {
                $announcement->is_active = 0;
            }

            if ($announcement->save()) {
                $request->session()->flash('success', 'Başarıla Güncellendi');
            } else {
                $request->session()->flash('error', 'Güncelleme Başarısız');
            }
            session()->flash('tab_page','context');                 //Which tab will be opened.
            return back();
        }else if($request->has('updateannouncementorder')){

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Returns locations of announcement's on order and re-assigns orders in order to that locations.
             */
            $orders = explode(',',$request->result_h_flow);
            $announcements = Announcement::where('lang_code',$request->lang_code)->orderBy('order','asc')->get();
            $i=0;
            foreach($orders as $order){
                $announcements[$order]->order = $i+1;
                $announcements[$order]->save();
                $i++;
            }

            $request->session()->flash('success', 'Başarılı');
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
        $announcement = Announcement::findOrFail($id);
        $lang_code = $announcement->lang_code;

        foreach (Announcement::where('uid', $announcement->uid)->get() as $item) {
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Re-order after deleting process.
             */
            foreach (Announcement::where('lang_code', $item->lang_code)->where('order', '>', $item->order)->get() as $o_item) {
                $o_item->order = $o_item->order - 1;
                $o_item->save();
            }
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Delete images of that annnouncement.
             */
            foreach(AnnouncementImage::where('announcement_id',$item->id)->get() as $image){
                if(file_exists($image->image)){
                    unlink($image->image);
                }
                if(file_exists($image->thumbnail)){
                    unlink($image->thumbnail);
                }
                $image->delete();
            }
            $item->delete();
        }
        session()->flash('success', 'Deleted');
        return redirect()->route('admin.announcement-settings.index', ['lang_code' => $lang_code]);
    }
}
