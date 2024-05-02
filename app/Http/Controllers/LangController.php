<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AnnouncementImage;
use App\Models\Language;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductCategory;
use App\Models\ProductCategoryPivot;
use App\Models\ImageManager;
use App\Services\FileManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class LangController extends Controller
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

        if($request->refresh){
            return back();
        }
        return view('admin.lang-settings', ['langs' => Language::orderBy('order','asc')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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

        $langCodes = config('app.available_locales');
        return view('admin.lang-settings-add',[
            'image_size' => ImageManager::where('type','dil_icon')->first(),
            'langCodes' => $langCodes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::allows('master') || Gate::allows('admin')) {

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Is the language name unique ?
             */
            foreach(Language::all() as $lang){
                if(trim($lang->lang_code) == trim($request->lang_code)){
                    $request->session()->flash('same_lang_code_error','Aynı Dil Kodu.');
                    return back();
                }
            }

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Create the data.
             */
            $lang = new Language();

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Validation for file mime from FileManagerService.
             */
            if($request->hasFile('icon')){
                if(!$this->fileManagerService->checkExtension($request->favicon)){
                    $request->session()->flash('file_extension_error','Dosya uzantısı.');
                    return back();
                }
            }

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Upload cropped image if there is one.
             */
            if ($request->cropped_data_favicon) {
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                * Quality, fize size, image sizes.
                */
                $imageManager = ImageManager::where('type', 'dil_icon')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($request->title,$request->cropped_data_favicon,'icons',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                $lang->icon = $result;
            }

            $lang->lang_code = $request->lang_code;
            $lang->lang_name = $request->lang_name;

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Is the language actiev or passive.
             */
            if ('on' == $request->is_active) {
                $lang->is_active = 1;
            } else {
                $lang->is_active = 0;
            }

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Add to last in order.
             */
            $order = 0;
            foreach (Language::all() as $a) {
                if ($a->order > $order) {
                    $order = $a->order;
                }
            }
            $lang->order = $order + 1;

            if ($lang->save()) {

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * On creating new language auto generate data that has auto generating.
                 * Based on default language's data.
                 * Call data and copy it for new language's data.
                 */
                $categories = ProductCategory::where('lang_code', $request->lang_code)->get();
                if (0 == $categories->count()) {
                    foreach (ProductCategory::where('lang_code', session('lang_code'))->get() as $category) {
                        $newCategory = new ProductCategory();
                        $newCategory->image_small_screen = $category->image_small_screen;
                        $newCategory->image_large_screen = $category->image_large_screen;
                        $newCategory->hero = $category->hero;
                        $newCategory->name = '-';
                        $newCategory->description = '-';
                        $newCategory->order = $category->order;
                        $newCategory->rank = $category->rank;
                        $newCategory->is_active = $category->is_active;
                        $newCategory->parent_category_uid = $category->parent_category_uid;
                        $newCategory->lang_code = $request->lang_code;
                        $newCategory->uid = $category->uid;
                        $newCategory->slug = 'temp';
                        $newCategory->save();
                        $newCategory->slug = Str::slug(uniqid().'-'.$newCategory->id);
                        $newCategory->save();
                    }
                }
                $products = Product::where('lang_code', $request->lang_code)->get();
                if (0 == $products->count()) {
                    foreach (Product::where('lang_code', session('lang_code'))->get() as $product) {
                        $newProduct = new Product();
                        $newProduct->image_small_screen = $product->image_small_screen;
                        $newProduct->image_large_screen = $product->image_large_screen;
                        $newProduct->thumbnail_small_screen = $product->thumbnail_small_screen;
                        $newProduct->thumbnail_large_screen = $product->thumbnail_large_screen;
                        $newProduct->thumbnail2_small_screen = $product->thumbnail2_small_screen;
                        $newProduct->thumbnail2_large_screen = $product->thumbnail2_large_screen;
                        $newProduct->hero = $product->hero;
                        $newProduct->name = '-';
                        $newProduct->description = '-';
                        $newProduct->order = $product->order;
                        $newProduct->price = $product->price;
                        $newProduct->is_active = $product->is_active;
                        $newProduct->product_no = $product->product_no;
                        $newProduct->product_url = $product->product_url;
                        $newProduct->lang_code = $request->lang_code;
                        $newProduct->uid = $product->uid;
                        $newProduct->slug = 'temp';
                        $newProduct->save();
                        $newProduct->slug = Str::slug(uniqid().'-'.$newProduct->id);
                        $newProduct->save();

                        /** ►►►►► DEVELOPER ◄◄◄◄◄
                         * Product and ProductCategory's pivot table.
                         * New product ID
                         * Category's UID with Product Category ID
                         * New generated Category's ID with Category UID and new language.
                         * UID is same on different languages.
                         */
                        foreach (ProductCategoryPivot::where('product_id', $product->id)->get() as $pivot) {
                            $newPivot = new ProductCategoryPivot();
                            $newPivot->product_id = $newProduct->id;

                            $categoryUID = ProductCategory::where('id', $pivot->category_id)->first()->uid;
                            $categoryID = ProductCategory::where('uid', $categoryUID)->where('lang_code', $request->lang_code)->first()->id;
                            //$categoryID = $newCategory->id;
                            $newPivot->category_id = $categoryID;

                            /** ►►►►► DEVELOPER ◄◄◄◄◄
                             * Categories order.
                             * Products has different order in categories and different order each other.
                             */
                            $pivot_order = 0;
                            foreach (ProductCategoryPivot::where('category_id',$categoryID)->get() as $a) {
                                if ($a->product_order > $pivot_order) {
                                    $pivot_order = $a->product_order;
                                }
                            }
                            $newPivot->product_order = $pivot_order + 1;
                            $newPivot->save();
                        }

                        /** ►►►►► DEVELOPER ◄◄◄◄◄
                         * Copy Product's images for every product.
                         */
                        foreach (ProductImage::where('product_id', $product->id)->get() as $image) {
                            $newImage = new ProductImage();
                            $newImage->product_id = $newProduct->id;
                            $newImage->image = $image->image;
                            $newImage->thumbnail = $image->thumbnail;
                            $newImage->thumbnail2 = $image->thumbnail2;
                            $newImage->order = $image->order;
                            $newImage->save();
                        }
                    }
                }
                $announcement = Announcement::where('lang_code', $request->lang_code)->get();
                if (0 == $products->count()) {
                    foreach (Announcement::where('lang_code', session('lang_code'))->get() as $announcement) {
                        $newAnnouncement = new Announcement();
                        $newAnnouncement->image_small_screen = $announcement->image_small_screen;
                        $newAnnouncement->image_large_screen = $announcement->image_large_screen;
                        $newAnnouncement->thumbnail_small_screen = $announcement->thumbnail_small_screen;
                        $newAnnouncement->thumbnail_large_screen = $announcement->thumbnail_large_screen;
                        $newAnnouncement->hero = $announcement->hero;
                        $newAnnouncement->title = '-';
                        $newAnnouncement->author = $announcement->author;
                        $newAnnouncement->short_description = '-';
                        $newAnnouncement->announcement_date = $announcement->announcement_date;
                        $newAnnouncement->description = '-';
                        $newAnnouncement->is_active = $announcement->is_active;
                        $newAnnouncement->order = $announcement->order;
                        $newAnnouncement->uid = $announcement->uid;
                        $newAnnouncement->lang_code = $request->lang_code;
                        $newAnnouncement->video_embed_code = $announcement->video_embed_code;
                        $newAnnouncement->slug = 'temp';
                        $newAnnouncement->save();
                        $newAnnouncement->slug = Str::slug(uniqid().'-'.$newAnnouncement->id);
                        $newAnnouncement->save();



                        /** ►►►►► DEVELOPER ◄◄◄◄◄
                         * Copy Announcement's images for every announcement.
                         */
                        foreach (AnnouncementImage::where('announcement_id', $announcement->id)->get() as $image) {
                            $newImage = new AnnouncementImage();
                            $newImage->announcement_id = $newAnnouncement->id;      //yeni oluşturulan ürünün id'si
                            $newImage->image = $image->image;                       //aynı resim
                            $newImage->thumbnail = $image->thumbnail;
                            $newImage->order = $image->order;                       //aynı sıra
                            $newImage->save();
                        }
                    }
                }
                $request->session()->flash('success', 'Kayıt Başarılı');
            } else {
                $request->session()->flash('error', 'Kayıt Başarısız');
            }
            return redirect()->route('admin.lang-settings.index');
        } else {
            return view('admin.lang-settings', ['langs' => Language::orderBy('order','asc')->get()]);
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
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * User Preferences Sessions for language,
         */
        if(!session('lang_code')){
            session()->forget('lang_code');
            session()->put('lang_code', App::getLocale());
        }else{
            App::setLocale(session('lang_code'));
        }

        $langCodes = config('app.available_locales');
        return view('admin.lang-settings-update', [
            'lang' => Language::findOrFail($id),
            'langs' => Language::orderBy('order','asc')->get(),
            'image_size' => ImageManager::where('type','dil_icon')->first(),
            'langCodes' => $langCodes
        ]);
    }

    /** ►►►►► DEVELOPER ◄◄◄◄◄
     * Order page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editOrder(Request $request)
    {
        $langs = Language::orderBy('order','asc')->get();
        if(count($langs) == 0){
            $request->session()->flash('no_lang','Dil Kaydı Bulunamadı');
            return back();
        }
        return view('admin.lang-settings-update-order', [
            'langs' => $langs,
        ]);
    }

    /** ►►►►► DEVELOPER ◄◄◄◄◄
     * IDs from string array.
     */
    public function dataFromSingleLevelNested($data_string){
        $tmp_str = '';
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Removing "[,]".
         */
        for($i=1;$i<strlen($data_string)-1;$i++){
            $tmp_str=$tmp_str.$data_string[$i];
        }
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Splitting by ",".
         */
        $exploded_data = explode(',',$tmp_str);
        $clean_exploded_data = [];
        $tmp_str = '';
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Get IDs from items.
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
        if($request->has('updatelang')){
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * language name, active property, default can be updated.
             */

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Is the language name unique ?
             */
            foreach(Language::all() as $lang){
                if(trim($lang->lang_code) == trim($request->lang_code)){
                    $request->session()->flash('same_lang_code_error','Aynı Dil Kodu.');
                    return back();
                }
            }
            $lang = Language::findOrFail($id);
            $lang->lang_name = $request->lang_name;
            if($lang->lang_code != session('lang_code')){   //Default language can not be passive.
                if ('on' == $request->is_active) {
                    $lang->is_active = 1;
                } else {
                    $lang->is_active = 0;
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Passive language can not be default.
                 */
                if(!$request->is_active && $request->is_default == 'on'){
                    $request->session()->flash('passive_default_error','Pasif dil varsayılan olamaz.');
                    return redirect()->route('admin.lang-settings.edit', [$id]);
                }
            }

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Validation for file mime from FileManagerService.
             */
            if($request->hasFile('icon')){
                if(!$this->fileManagerService->checkExtension($request->favicon)){
                    $request->session()->flash('file_extension_error','Dosya uzantısı.');
                    return back();
                }
            }
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Upload cropped image if there is one.
             */
            if ($request->cropped_data_favicon) {
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                * Quality, fize size, image sizes.
                */
                $imageManager = ImageManager::where('type', 'dil_icon')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($lang->lang_name,$request->cropped_data_favicon,'icons',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old files if there is.
                 */
                if (file_exists($lang->icon)) {
                    unlink($lang->icon);
                }
                $lang->icon = $result;
            }

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Reset old default before making another default language.
             */
            if ('on' == $request->is_default) {
                foreach (Language::where('is_default', 1)->get() as $item) {
                    $item->is_default = 0;
                    $item->save();
                }
                $lang->is_default = 1;
            }


            if ($lang->save()) {
                $request->session()->flash('success', 'Güncelleme Başarılı');
            } else {
                $request->session()->flash('error', 'Güncelleme Başarısız');
            }
            return back();
        }else if($request->has('updatelangssorder')){
            $sorted_ids = $this->dataFromSingleLevelNested($request->nestable_output);
            if($sorted_ids[0] == ''){
                $request->session()->flash('error', 'Başarısız');
                return back();
            }
            $counter = 1;
            foreach($sorted_ids as $id){
                $lang = Language::findOrFail($id);
                $lang->order = $counter;
                $lang->save();
                $counter++;
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
        //
    }
}
