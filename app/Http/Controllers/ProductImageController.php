<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use App\Models\Product;
use App\Models\Language;
use App\Models\ImageManager;
use App\Services\FileManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ProductImageController extends Controller
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

        $image = ProductImage::findOrFail($id);
        $parentProduct = Product::where('id',$image->product_id)->first();
        $images = ProductImage::where('product_id', $image->product_id)->orderBy('order', 'asc')->get();
        return view('admin.products-images-update', [
            'image' => $image,
            'images' => $images,
            'image_size' => ImageManager::where('type','ürün_resim')->first(),
            'langs' => Language::all(),
            'lang_code' => $parentProduct->lang_code
        ]);
    }

    /** ►►►►► DEVELOPER ◄◄◄◄◄
     * Order update page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
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

        $images = ProductImage::where('product_id', $request->product_id)->orderBy('order', 'asc')->get();
        if(count($images) == 0){
            $request->session()->flash('no_image','Resim Kaydı Bulunamadı');
            return back();
        }
        return view('admin.products-images-update-order', [
            'images' => $images,
            'langs' => Language::all()
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
                $imageManager = ImageManager::where('type', 'ürün_resim')->first();
                $image = ProductImage::findOrFail($id);
                $productName = Product::findOrFail($image->product_id)->name;
                $imageManagerThumbnail = ImageManager::where('type','ürün_thumbnail_geniş')->first();
                $imageManagerThumbnail2 = ImageManager::where('type','ürün_thumbnail2_geniş')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($productName, $request->cropped_data_multi, 'products', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading first thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult = $this->fileManagerService->uploadImage($productName, $request->cropped_data_multi, 'products/thumbnail', $imageManagerThumbnail);
                if($thumbnailResult == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading second thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult2 = $this->fileManagerService->uploadImage($productName, $request->cropped_data_multi, 'products/thumbnail2', $imageManagerThumbnail2);
                if($thumbnailResult2 == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                if (file_exists($image->image)) {
                    unlink($image->image);
                }
                if (file_exists($image->thumbnail)) {
                    unlink($image->thumbnail);
                }
                if (file_exists($image->thumbnail2)) {
                    unlink($image->thumbnail2);
                }
                $image->image = $result;
                $image->thumbnail = $thumbnailResult;
                $image->thumbnail2 = $thumbnailResult2;

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
                $image = ProductImage::findOrFail($id);

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

        }else if($request->has('updateproductimagesorder')){
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Returns locations of announcement's on order and re-assigns orders in order to that locations.
             */
            $orders = explode(',',$request->result_h_flow);
            $productImages = ProductImage::where('product_id',$request->product_id)->orderBy('order','asc')->get();
            $i=0;
            foreach($orders as $order){
                $productImages[$order]->order = $i+1;
                $productImages[$order]->save();
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
        $productImage = ProductImage::findOrFail($id);
        $productID = $productImage->product_id;
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Re-order after deleting process.
         */
        foreach (ProductImage::where('product_id', $productImage->product_id)->where('order', '>', $productImage->order)->get() as $o_item) {
            $o_item->order = $o_item->order - 1;
            $o_item->save();
        }
        $productImage->delete();
        session()->flash('success', 'Deleted');

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * If no image has left.
         */
        if(0 >= count(ProductImage::where('product_id', $productID)->get())){
            return redirect()->route('admin.products-settings.index', ['lang_code' => Product::where('id',$productID)->first()->lang_code]);
        }
        return back();
    }
}
