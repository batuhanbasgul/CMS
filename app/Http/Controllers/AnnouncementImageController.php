<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageManager;
use App\Models\Announcement;
use App\Models\AnnouncementImage;
use App\Models\Language;
use Illuminate\Support\Facades\App;
use App\Services\FileManagerService;

class AnnouncementImageController extends Controller
{
    //MORE DETAILED IMAGES AND POSTS RATHER THAN GENERAL GALLERY FOR SUB-CONTENTS

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
         * User Preferences Sessions for language,
         */
        if(!session('lang_code')){
            session()->forget('lang_code');
            session()->put('lang_code', App::getLocale());
        }else{
            App::setLocale(session('lang_code'));
        }

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Send Selected image and images that connected to that image to Update.
         * All images sends because of showing them on order selection.
         */
        $image = AnnouncementImage::findOrFail($id);
        $parentAnnouncement = Announcement::where('id',$image->announcement_id)->first();
        $images = AnnouncementImage::where('announcement_id', $image->announcement_id)->orderBy('order', 'asc')->get();
        return view('admin.announcements-images-update', [
            'image' => $image,
            'langs' => Language::all(),
            'images' => $images,
            'image_size' => ImageManager::where('type','duyuru_resim')->first(),
            'lang_code' => $parentAnnouncement->lang_code
        ]);
    }

    /** ►►►►► DEVELOPER ◄◄◄◄◄
     * Order update page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editOrder(Request $request)
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
        $images = AnnouncementImage::where('announcement_id', $request->announcement_id)->orderBy('order', 'asc')->get();
        if(count($images) == 0){
            $request->session()->flash('no_image','Resim Kaydı Bulunamadı');
            return back();
        }
        return view('admin.announcements-images-update-order', [
            'images' => $images,
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
        if($request->has('updateimagemulti')){
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
                $image = AnnouncementImage::findOrFail($id);
                $announcementName = Announcement::findOrFail($image->announcement_id)->title;
                $imageManagerThumbnail = ImageManager::where('type','duyuru_thumbnail_geniş')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($announcementName, $request->cropped_data_multi, 'announcements', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult = $this->fileManagerService->uploadImage($announcementName, $request->cropped_data_multi, 'announcements/thumbnail', $imageManagerThumbnail);
                if($thumbnailResult == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old files if there is.
                 */
                if (file_exists($image->image)) {
                    unlink($image->image);
                }
                if (file_exists($image->thumbnail)) {
                    unlink($image->thumbnail);
                }
                $image->image = $result;
                $image->thumbnail = $thumbnailResult;

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Switch tool returns 'on' or null.
                 */
                if ('on' == $request->is_active) {
                    $image->is_active = 1;
                } else {
                    $image->is_active = 0;
                }

                if ($image->save()) {
                    $request->session()->flash('success', 'Başarılı');
                } else {
                    $request->session()->flash('error', 'Başarısız');
                }
                return back();
            }else{
                $image = AnnouncementImage::findOrFail($id);

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Switch tool returns 'on' or null.
                 */
                if ('on' == $request->is_active) {
                    $image->is_active = 1;
                } else {
                    $image->is_active = 0;
                }

                if ($image->save()) {
                    $request->session()->flash('success', 'Başarılı');
                } else {
                    $request->session()->flash('error', 'Başarısız');
                }
                return back();
            }

        }else if($request->has('updateannouncementimagesorder')){
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Returns locations of announcement's on order and re-assigns orders in order to that locations.
             */
            $orders = explode(',',$request->result_h_flow);
            $announcementImages = AnnouncementImage::where('announcement_id',$request->announcement_id)->orderBy('order','asc')->get();
            $i=0;
            foreach($orders as $order){
                $announcementImages[$order]->order = $i+1;
                $announcementImages[$order]->save();
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
        $announcementImage = AnnouncementImage::findOrFail($id);
        $announcementID = $announcementImage->announcement_id;
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Re-order after deleting process.
         */
        foreach (AnnouncementImage::where('announcement_id', $announcementID)->where('order', '>', $announcementImage->order)->get() as $o_item) {
            $o_item->order = $o_item->order - 1;
            $o_item->save();
        }
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Delete images of that annnouncement.
         */
        $announcementImage->delete();
        session()->flash('success', 'Deleted');
        session()->flash('tab_page','image_multi');

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * If no image has left.
         */
        if(0 >= count(AnnouncementImage::where('announcement_id', $announcementID)->get())){
            return redirect()->route('admin.announcement-settings.index', ['lang_code' => Announcement::where('id',$announcementID)->first()->lang_code]);
        }
        return back();
    }
}
