<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductCategoryPivot;
use App\Models\ProductImage;
use App\Models\ImageManager;
use App\Services\FileManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class ProductsSettingsController extends Controller
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
         * if not null and there is content for that language, reroutes to edit page with lang_code, products, categories, langs parameters.
         */
        if ($request->lang_code) {
            $products = Product::where('lang_code', $request->lang_code)->get();
            return view('admin.products-settings', [
                'lang_code' => $request->lang_code,
                'products' => $products,
                'langs' => Language::orderBy('order','asc')->get(),
                'categories' => ProductCategory::where('lang_code',$request->lang_code),
                'large_size' => ImageManager::where('type','ürün_resim_geniş')->first(),
                'mobile_size' => ImageManager::where('type','ürün_resim_mobil')->first(),
                'banner_size' => ImageManager::where('type','ürün_banner')->first(),
                'image_size' => ImageManager::where('type','ürün_resim')->first()
            ]);
        } else {
            $products = Product::where('lang_code', session('lang_code'))->get();
            return view('admin.products-settings', [
                'lang_code' => session('lang_code'),
                'products' => $products,
                'langs' => Language::orderBy('order','asc')->get(),
                'categories' =>  ProductCategory::where('lang_code',session('lang_code')),
                'large_size' => ImageManager::where('type','ürün_resim_geniş')->first(),
                'mobile_size' => ImageManager::where('type','ürün_resim_mobil')->first(),
                'banner_size' => ImageManager::where('type','ürün_banner')->first(),
                'image_size' => ImageManager::where('type','ürün_resim')->first()
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
         * Return back if there is no category to add product in.
         */
        if(0 >= count(ProductCategory::where('lang_code',$request->lang_code)->get())){
            session()->flash('no_category');
            return back();
        }
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * If lang code selected uses that else it uses default language.
         * With categories that language code.
         * Route to create product.
         */
        if ($request->lang_code) {
            $categories = ProductCategory::where('lang_code', $request->lang_code)->get();
            return view('admin.products-settings-add', ['lang_code' => $request->lang_code, 'langs' => Language::all(), 'categories' => $categories]);
        } else {
            $categories = ProductCategory::where('lang_code', session('lang_code'))->get();
            return view('admin.products-settings-add', ['lang_code' => session('lang_code'), 'langs' => Language::all(), 'categories' => $categories]);
        }
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

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->keywords = $request->keywords;
        $product->price = $request->price;
        $product->product_no = $request->product_no;
        $product->lang_code = $request->lang_code;
        $product->product_url = $request->product_url;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * On same content but different languages has same "menu_code" for matching.
         * UID for just one menu and one content.
         */
        $product->uid = $uniqueid;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Add to last order.
         */
        $product_order = 0;
        foreach (Product::where('lang_code', $request->lang_code)->get() as $a) {
            if ($a->order > $product_order) {
                $product_order = $a->order;
            }
        }
        $product->order = $product_order + 1;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * SLUG generating with ID.
         */
        $product->slug = 'temp';
        $product->save();
        $product->slug = Str::slug($request->name.'-'.$product->id);

        if ($product->save()) {
            $product_id = $product->id; //For routing to edit page.
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Save to pivot table with it's categories by that product's ID on creating.
             */
            foreach ($request->product_category_uid as $uid) {
                $category = ProductCategory::where('uid', $uid)->where('lang_code', $request->lang_code)->first();
                $pivot = new ProductCategoryPivot();
                $pivot->product_id = $product->id;
                $pivot->category_id = $category->id;
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Product sorting in the category, not the general sorting.
                 */
                $pivot_order = 0;
                foreach (ProductCategoryPivot::where('category_id',$category->id)->get() as $a) {
                    if ($a->product_order > $pivot_order) {
                        $pivot_order = $a->product_order;
                    }
                }
                $pivot->product_order = $pivot_order + 1;
                $pivot->save();
            }

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * If there is other languages , create automatic data with images.
             */
            foreach (Language::all() as $lang) {
                if ($request->lang_code != $lang->lang_code) {
                    $product = new Product();
                    $product->name = '-';
                    $product->description = '-';
                    $product->keywords = '-';
                    $product->order = $product_order + 1;
                    $product->price = $request->price;
                    $product->product_no = $request->product_no;
                    $product->product_url = $request->product_url;
                    $product->lang_code = $lang->lang_code;
                    $product->uid = $uniqueid;

                    /** ►►►►► DEVELOPER ◄◄◄◄◄
                     * SLUG generating with ID.
                     */
                    $product->slug = 'temp';
                    $product->save();
                    $product->slug = Str::slug(uniqid().'-'.$product->id);
                    $product->save();

                    /** ►►►►► DEVELOPER ◄◄◄◄◄
                     * Save to pivot table with it's categories by that product's ID on creating.
                     */
                    foreach ($request->product_category_uid as $uid) {
                        $category = ProductCategory::where('uid', $uid)->where('lang_code', $product->lang_code)->first();
                        $pivot = new ProductCategoryPivot();
                        $pivot->product_id = $product->id;
                        $pivot->category_id = $category->id;
                        /** ►►►►► DEVELOPER ◄◄◄◄◄
                         * Product sorting in the category, not the general sorting.
                         */
                        $pivot_order = 0;
                        foreach (ProductCategoryPivot::where('category_id',$category->id)->get() as $a) {
                            if ($a->product_order > $pivot_order) {
                                $pivot_order = $a->product_order;
                            }
                        }
                        $pivot->product_order = $pivot_order + 1;
                        $pivot->save();
                    }
                }
            }
            $request->session()->flash('success', 'Başarılı');
        } else {
            $request->session()->flash('error', 'Başarısız');
        }
        return redirect()->route('admin.products-settings.edit', [$product_id, 'lang_code' => $request->lang_code]);
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
    public function edit(Request $request, $id)
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

        $product = Product::findOrFail($id);
        $products = Product::where('lang_code', $product->lang_code)->orderBy('order', 'asc')->get();
        $categories = ProductCategory::where('lang_code', $product->lang_code)->get();
        $pivots = ProductCategoryPivot::where('product_id', $product->id)->get();
        $images = ProductImage::where('product_id', $product->id)->orderBy('order', 'asc')->get();

        return view('admin.products-settings-update', [
            'categories' => $categories,
            'product' => $product,
            'images' => $images,
            'products' => $products,
            'langs' => Language::all(),
            'pivots' => $pivots,    //To select this product's categories on select box.
            'lang_code' => $product->lang_code,
            'large_size' => ImageManager::where('type','ürün_resim_geniş')->first(),
            'mobile_size' => ImageManager::where('type','ürün_resim_mobil')->first(),
            'banner_size' => ImageManager::where('type','ürün_banner')->first(),
            'image_size' => ImageManager::where('type','ürün_resim')->first()
        ]);
    }

    /** ►►►►► DEVELOPER ◄◄◄◄◄
     * Order management page.
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

        $products = Product::where('lang_code', $request->lang_code)->orderBy('order','asc')->get();
        if(count($products) == 0){
            $request->session()->flash('no_product','Ürün Kaydı Bulunamadı');
            return back();
        }
        return view('admin.products-settings-update-order', [
            'products' => $products,
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
        $product = Product::findOrFail($id);

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
                $imageManager = ImageManager::where('type', 'ürün_resim_geniş')->first();
                $imageManagerThumbnail = ImageManager::where('type','ürün_thumbnail_geniş')->first();
                $imageManagerThumbnail2 = ImageManager::where('type','ürün_thumbnail2_geniş')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($product->name.'-l', $request->cropped_data, 'images', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading first thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult = $this->fileManagerService->uploadImage($product->name.'-l', $request->cropped_data, 'images/thumbnail', $imageManagerThumbnail);
                if($thumbnailResult == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading second thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult2 = $this->fileManagerService->uploadImage($product->name.'-l', $request->cropped_data, 'images/thumbnail2', $imageManagerThumbnail2);
                if($thumbnailResult2 == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old files if there is.
                 */
                if (file_exists($product->image_large_screen)) {
                    unlink($product->image_large_screen);
                }
                if (file_exists($product->thumbnail_large_screen)) {
                    unlink($product->thumbnail_large_screen);
                }
                if (file_exists($product->thumbnail2_large_screen)) {
                    unlink($product->thumbnail2_large_screen);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $product->image_large_screen = $result;
                $product->thumbnail_large_screen = $thumbnailResult;
                $product->thumbnail2_large_screen = $thumbnailResult2;
                if ($product->save()) {
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
                $imageManager = ImageManager::where('type', 'ürün_resim_mobil')->first();
                $imageManagerThumbnail = ImageManager::where('type','ürün_thumbnail_mobil')->first();
                $imageManagerThumbnail2 = ImageManager::where('type','ürün_thumbnail2_mobil')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($product->name.'-m', $request->cropped_data_mobile, 'images', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading first thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult = $this->fileManagerService->uploadImage($product->name.'-m', $request->cropped_data_mobile, 'images/thumbnail', $imageManagerThumbnail);
                if($thumbnailResult == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading second thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult2 = $this->fileManagerService->uploadImage($product->name.'-m', $request->cropped_data_mobile, 'images/thumbnail2', $imageManagerThumbnail2);
                if($thumbnailResult2 == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old files if there is.
                 */
                if (file_exists($product->image_small_screen)) {
                    unlink($product->image_small_screen);
                }
                if (file_exists($product->thumbnail_small_screen)) {
                    unlink($product->thumbnail_small_screen);
                }
                if (file_exists($product->thumbnail2_small_screen)) {
                    unlink($product->thumbnail2_small_screen);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $product->image_small_screen = $result;
                $product->thumbnail_small_screen = $thumbnailResult;
                $product->thumbnail2_small_screen = $thumbnailResult2;
                if ($product->save()) {
                    $request->session()->flash('success', 'Ürün Güncellendi');
                } else {
                    $request->session()->flash('error', 'Ürün Güncellenemedi');
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
                $imageManager = ImageManager::where('type', 'ürün_resim')->first();
                $imageManagerThumbnail = ImageManager::where('type','ürün_thumbnail_geniş')->first();
                $imageManagerThumbnail2 = ImageManager::where('type','ürün_thumbnail2_geniş')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($product->name, $request->cropped_data_multi, 'products', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading first thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult = $this->fileManagerService->uploadImage($product->name, $request->cropped_data_multi, 'products/thumbnail', $imageManagerThumbnail);
                if($thumbnailResult == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading second thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult2 = $this->fileManagerService->uploadImage($product->name, $request->cropped_data_multi, 'products/thumbnail2', $imageManagerThumbnail2);
                if($thumbnailResult2 == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Product images are in different table on database.
                 * Adding on last order.
                 * UID is same on different languages for same images. But saves them their ids to changing them independently. (Order and auto generating)
                 */
                if (ProductImage::where('product_id', $product->id)->orderBy('id', 'desc')->get()->count() > 0) {
                    /** ►►►►► DEVELOPER ◄◄◄◄◄
                     * Last image order on that category.
                     * Checking every time, categories can be different.
                     */
                    $order = ProductImage::where('product_id', $product->id)->orderBy('order', 'desc')->first()->order;
                } else {
                    $order = 0;
                }
                $order += 1;
                foreach (Product::where('uid', $product->uid)->get() as $p) {
                    $productImage = new ProductImage();
                    $productImage->image = $result;
                    $productImage->thumbnail = $thumbnailResult;
                    $productImage->thumbnail2 = $thumbnailResult2;
                    $productImage->order = $order;
                    $productImage->product_id = $p->id;
                    $productImage->save();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                if ($product->save()) {
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
                $imageManager = ImageManager::where('type', 'ürün_banner')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($product->name.'-b', $request->cropped_data_banner, 'hero', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old files if there is.
                 */
                if (file_exists($product->hero)) {
                    unlink($product->hero);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $product->hero = $result;
                if ($product->save()) {
                    $request->session()->flash('success', 'Ürün Güncellendi');
                } else {
                    $request->session()->flash('error', 'Ürün Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_banner');                 //Which tab will be opened.
            return back();
        }else if($request->has('updateproductssettings')){

            $product->name = $request->name;
            $product->description = $request->description;
            $product->keywords = $request->keywords;
            $product->price = $request->price;
            $product->product_no = $request->product_no;
            $product->product_url = $request->product_url;

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Updating slug with old slug number.
             */
            $product->slug = Str::slug($request->name.'-'.last(explode('-',$product->slug)));

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Clear old pivot table and create it with new categories.
             */
            foreach (ProductCategoryPivot::where('product_id', $product->id)->get() as $pivot) {
                $pivot->delete();
            }
            foreach ($request->product_category_uid as $uid) {
                $category = ProductCategory::where('uid', $uid)->where('lang_code', $product->lang_code)->first();
                $pivot = new ProductCategoryPivot();
                $pivot->product_id = $product->id;
                $pivot->category_id = $category->id;
                $previous_order = 0;
                $status = 0;
                foreach(ProductCategoryPivot::where('category_id',$category->id)->orderBy('product_order','asc')->get() as $a){
                    if($a->product_order-$previous_order > 1){
                        $pivot->product_order = $a->product_order - 1;
                        $status = 1;
                        break;
                    }
                }
                if(!$status){
                    $pivot->product_order = ProductCategoryPivot::where('category_id',$category->id)->get()->count() + 1;
                }
                $pivot->save();
            }

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Switch tool returns 'on' or null.
             */
            if ('on' == $request->is_active) {
                $product->is_active = 1;
            } else {
                $product->is_active = 0;
            }
            if ($product->save()) {
                $request->session()->flash('success', 'Başarılı');
            } else {
                $request->session()->flash('error', 'Başarısız');
            }
            session()->flash('tab_page','context');                 //Which tab will be opened.
            return back();
        }else if($request->has('updateproductsorder')){

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Returns locations of product's on order and re-assigns orders in order to that locations.
             */
            $orders = explode(',',$request->result_h_flow);
            $products = Product::where('lang_code',$request->lang_code)->orderBy('order','asc')->get();
            $i=0;
            foreach($orders as $order){
                $products[$order]->order = $i+1;
                $products[$order]->save();
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
        $product = Product::findOrFail($id);
        $lang_code = $product->lang_code;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Get other products by uid on other languages.
         */
        $product_ids = [];
        foreach(Product::where('uid',$product->uid)->get() as $p){
            array_push($product_ids,$p->id);
        }

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Delete other products on other languages on pivots.
         */
        foreach($product_ids as $product_id){
            foreach (ProductCategoryPivot::where('product_id', $product_id)->get() as $pivot) {
                $pivot->delete();
            }
        }
        foreach (Product::where('uid', $product->uid)->get() as $item) {
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Re-order after deleting process.
             */
            foreach (Product::where('lang_code', $item->lang_code)->where('order', '>', $item->order)->get() as $o_item) {
                $o_item->order = $o_item->order - 1;
                $o_item->save();
            }
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Delete other images of that product.
             */
            foreach(ProductImage::where('product_id',$item->id)->get() as $image){
                if(file_exists($image->image)){
                    unlink($image->image);
                }
                $image->delete();
            }
            $item->delete();
        }
        session()->flash('success', 'Deleted');
        return redirect()->route('admin.products-settings.index', ['lang_code' => $lang_code]);
    }
}
