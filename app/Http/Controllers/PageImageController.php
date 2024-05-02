<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageManager;
use App\Models\PageImage;
use App\Models\Page;
use App\Models\Language;
use App\Services\FileManagerService;
use Illuminate\Support\Facades\App;

class PageImageController extends Controller
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
         * Send Selected image and images that connected to that image to Update.
         * All images sends because of showing them on order selection.
         */
        $image = PageImage::findOrFail($id);
        $images = PageImage::where('page_id', $image->page_id)->orderBy('order', 'asc')->get();
        return view('admin.page-images-update', ['image' => $image, 'images' => $images, 'image_size' => ImageManager::where('type','sayfa_resim')->first(),]);
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

        $images = PageImage::where('page_id', $request->page_id)->orderBy('order', 'asc')->get();
        if(count($images) == 0){
            $request->session()->flash('no_image','Resim Kaydı Bulunamadı');
            return back();
        }
        return view('admin.page-images-update-order', [
            'images' => $images,
            'langs' => Language::all()
        ]);
    }

    /** ►►►►► DEVELOPER ◄◄◄◄◄
     * ID Array from String Data.
     */
    public function dataFromSingleLevelNested($data_string){
        $tmp_str = '';
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * removing "[,]".
         */
        for($i=1;$i<strlen($data_string)-1;$i++){
            $tmp_str=$tmp_str.$data_string[$i];
        }
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Splitting with ",".
         */
        $exploded_data = explode(',',$tmp_str);
        $clean_exploded_data = [];
        $tmp_str = '';
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Getting IDs from items.
         */
        foreach($exploded_data as $item){
            for($i=0;$i<strlen($item);$i++){
                if(
                $item[$i] == '0' ||$item[$i] == '1' ||$item[$i] == '2' ||$item[$i] == '3' ||$item[$i] == '4' ||
                $item[$i] == '5' ||$item[$i] == '6' ||$item[$i] == '7' ||$item[$i] == '8' ||$item[$i] == '9'
                ){
                    $tmp_str = $tmp_str.$item[$i];
                }
            }
            array_push($clean_exploded_data,$tmp_str);
            $tmp_str = '';
        }
        return $clean_exploded_data;
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
                $imageManager = ImageManager::where('type', 'sayfa_resim')->first();
                $image = PageImage::findOrFail($id);
                $pageName = Page::findOrFail($image->page_id)->title;
                $imageManagerThumbnail = ImageManager::where('type','sayfa_thumbnail_geniş')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($pageName, $request->cropped_data_multi, 'pages', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult = $this->fileManagerService->uploadImage($pageName, $request->cropped_data_multi, 'pages/thumbnail', $imageManagerThumbnail);
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
                $image = PageImage::findOrFail($id);

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

        }else if($request->has('updatepageimagesorder')){
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Returns locations of announcement's on order and re-assigns orders in order to that locations.
             */
            $orders = explode(',',$request->result_h_flow);
            $pageImages = PageImage::where('page_id',$request->page_id)->orderBy('order','asc')->get();

            $i=0;
            foreach($orders as $order){
                $pageImages[$order]->order = $i+1;
                $pageImages[$order]->save();
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
        $pageImage = PageImage::findOrFail($id);
        $pageID = $pageImage->page_id;
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Re-order after deleting process.
         */
        foreach (PageImage::where('page_id', $pageImage->page_id)->where('order', '>', $pageImage->order)->get() as $o_item) {
            $o_item->order = $o_item->order - 1;
            $o_item->save();
        }
        $pageImage->delete();
        session()->flash('success', 'Deleted');

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * If no image has left.
         */
        if(0 >= count(PageImage::where('page_id', $pageID)->get())){
            return redirect()->route('admin.page-settings.index', ['lang_code' => Page::where('id',$pageID)->first()->lang_code]);
        }
        return back();
    }
}
