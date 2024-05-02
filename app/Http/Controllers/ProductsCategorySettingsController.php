<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductsInfo;
use App\Models\ProductCategoryPivot;
use App\Models\ImageManager;
use App\Services\FileManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class ProductsCategorySettingsController extends Controller
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
         * if not null and there is content for that language, reroutes to edit page with lang_code, categories, langs, image size parameters.
         */
        $mainCategory = ProductsInfo::where('lang_code', $request->lang_code)->first();
        if($mainCategory==null){
            return redirect()->route('admin.products-info-settings.index', ['lang_code' => $request->lang_code,'no_info' => true]);
        }
        if ($request->lang_code) {
            $categories = ProductCategory::where('lang_code', $request->lang_code)->get();
            return view('admin.products-category-settings', [
                'lang_code' => $request->lang_code,
                'categories' => $categories,
                'langs' => Language::orderBy('order','asc')->get(),
                'large_size' => ImageManager::where('type','ürün_kategori_içerik_resim_geniş')->first(),
                'mobile_size' => ImageManager::where('type','ürün_kategori_içerik_resim_mobil')->first(),
                'banner_size' => ImageManager::where('type','ürün_kategori_içerik_banner')->first(),
                'mainCategory' => $mainCategory,    //Ana kategori başlığı için
            ]);
        } else {
            $categories = ProductCategory::where('lang_code', session('lang_code'))->get();
            return view('admin.products-category-settings', [
                'lang_code' => session('lang_code'),
                'categories' => $categories,
                'langs' => Language::orderBy('order','asc')->get(),
                'large_size' => ImageManager::where('type','ürün_kategori_içerik_resim_geniş')->first(),
                'mobile_size' => ImageManager::where('type','ürün_kategori_içerik_resim_mobil')->first(),
                'banner_size' => ImageManager::where('type','ürün_kategori_içerik_banner')->first(),
                'mainCategory' => $mainCategory,    //Ana kategori başlığı için
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
         * If lang code selected uses that else it uses default language.
         * With categories that language code.
         * Route to create product.
         */
        if ($request->lang_code) {
            $mainCategory = ProductsInfo::where('lang_code', $request->lang_code)->first();
            if($mainCategory==null){
                return redirect()->route('admin.products-info-settings.index', ['lang_code' => $request->lang_code,'no_info' => true]);
            }
            $categories = ProductCategory::where('lang_code', $request->lang_code)->get();
            return view('admin.products-category-settings-add', [
                'lang_code' => $request->lang_code,
                'categories' => $categories,
                'langs' => Language::all(),
                'mainCategory' => $mainCategory
            ]);
        } else {
            $mainCategory = ProductsInfo::where('lang_code', session('lang_code'))->first();
            if($mainCategory==null){
                return redirect()->route('admin.products-info-settings.index', ['lang_code' => $request->lang_code]);
            }
            $categories = ProductCategory::where('lang_code', session('lang_code'))->get();
            return view('admin.products-category-settings-add', [
                'lang_code' => session('lang_code'),
                'categories' => $categories,
                'langs' => Language::all(),
                'mainCategory' => $mainCategory
            ]);
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

        $category = new ProductCategory();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->keywords = $request->keywords;


        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Add to last of order.
         */
        $order = 0;
        foreach (ProductCategory::where('lang_code', $request->lang_code)->get() as $a) {
            if ($a->order > $order) {
                $order = $a->order;
            }
        }
        $category->order = $order + 1;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Holds parent_category_uid there can be multiple parent category.
         * Every category has it's own uid, This way another category can be selected as parent.
         * parent_category_uid=0 means Main category which is products.
         */
        $category->parent_category_uid = $request->parent_category_uid;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * RANK system
         * if parent category is main, rank is 1,
         * if parent category is another category, rank is +1 of that category.
         */
        $parent_category = ProductCategory::where('uid',$request->parent_category_uid)->first();
        if($parent_category == null){
            $category->rank = 1;
        }else{
            $category->rank = $parent_category->rank+1;
        }
        $category->lang_code = $request->lang_code;
        $category->uid = $uniqueid;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * SLUG generating with ID.
         */
        $category->slug = 'temp';
        $category->save();
        $category->slug = Str::slug($request->name.'-'.$category->id);


        if ($category->save()) {
            $return_id = $category->id;

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Auto generate for other languages.
             */
            foreach (Language::all() as $lang) {
                if ($request->lang_code != $lang->lang_code) {
                    $category = new ProductCategory();
                    if ($request->hasFile('image_small_screen')) {
                        $file = $request->file('image_small_screen')->store('media/images');
                        $category->image_small_screen = $file;
                    }
                    if ($request->hasFile('image_large_screen')) {
                        $file = $request->file('image_large_screen')->store('media/images');
                        $category->image_small_screen = $file;
                    }
                    $category->name = '-';
                    $category->description = '-';
                    $category->keywords = '-';
                    $category->order = $order + 1;
                    $category->parent_category_uid = $request->parent_category_uid;

                    /** ►►►►► DEVELOPER ◄◄◄◄◄
                     * RANK system
                     * if parent category is main, rank is 1,
                     * if parent category is another category, rank is +1 of that category.
                     */
                    $parent_category = ProductCategory::where('uid',$request->parent_category_uid)->first();
                    if($parent_category == null){
                        $category->rank = 1;
                    }else{
                        $category->rank = $parent_category->rank+1;
                    }
                    $category->lang_code = $lang->lang_code;
                    $category->uid = $uniqueid;

                    /** ►►►►► DEVELOPER ◄◄◄◄◄
                     * SLUG generating with ID.
                     */
                    $category->slug = 'temp';
                    $category->save();
                    $category->slug = Str::slug(uniqid().'-'.$category->id);
                    $category->save();
                }
            }
            $request->session()->flash('success', 'Başarılı');
        } else {
            $request->session()->flash('error', 'Başarısız');
        }
        return redirect()->route('admin.products-category-settings.edit', [$return_id, 'lang_code' => $request->lang_code]);
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

    /** ►►►►► DEVELOPER ◄◄◄◄◄
     * Order management page.
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

        $categories = ProductCategory::where('lang_code', $request->lang_code)->orderBy('order','asc')->get();
        if(count($categories) == 0){
            $request->session()->flash('no_category','Kategori Kaydı Bulunamadı');
            return back();
        }
        return view('admin.products-category-settings-update-order', [
            'categories' => $categories,
            'langs' => Language::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
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

        $category = ProductCategory::findOrFail($id);
        $categories = ProductCategory::where('lang_code', $category->lang_code)->orderBy('order','asc')->get();
        $product_ids=[];
        foreach(ProductCategoryPivot::where('category_id',$id)->orderBy('product_order','asc')->get() as $pivot){
            array_push($product_ids, $pivot->product_id);
        }
        $category_products = [];
        for($i=0;$i<count($product_ids);$i++){
            array_push($category_products, Product::findOrFail($product_ids[$i]));
        }
        return view('admin.products-category-settings-update', [
            'categories' => $categories,
            'category_products' => $category_products,
            'category' => $category,
            'langs' => Language::all(),
            'large_size' => ImageManager::where('type','ürün_resim_geniş')->first(),
            'mobile_size' => ImageManager::where('type','ürün_resim_mobil')->first(),
            'banner_size' => ImageManager::where('type','ürün_banner')->first(),
            'image_size' => ImageManager::where('type','ürün_resim')->first()
        ]);
    }

    /** ►►►►► DEVELOPER ◄◄◄◄◄
     * ID array from string data.
     * Parent Menu UIDs are connected to parent child relation with rank of menu in nested structure.
     * UIDs are unique for one menu and one content, menu_code for content type and same for every same content even on languages.
     */
    public function saveDataRankToMultiLevelNested($data_string){
        $rank = 0;  //Rank ***
        $order_counter = 1; //General ordering, defines all order struct.
        $categories = [];
        for($i=0;$i<strlen($data_string);$i++){
            if($data_string[$i] == '['){
                $rank++;
            }else if($data_string[$i] == ']'){
                $rank--;
            }
            if(
            $data_string[$i] == '0' ||$data_string[$i] == '1' ||$data_string[$i] == '2' ||$data_string[$i] == '3' ||$data_string[$i] == '4' ||
            $data_string[$i] == '5' ||$data_string[$i] == '6' ||$data_string[$i] == '7' ||$data_string[$i] == '8' ||$data_string[$i] == '9'
            ){
                $tmp_id = '';
                while(true){
                    if(
                        $data_string[$i] == '0' ||$data_string[$i] == '1' ||$data_string[$i] == '2' ||$data_string[$i] == '3' ||$data_string[$i] == '4' ||
                        $data_string[$i] == '5' ||$data_string[$i] == '6' ||$data_string[$i] == '7' ||$data_string[$i] == '8' ||$data_string[$i] == '9'
                    ){
                        $tmp_id = $tmp_id.$data_string[$i];
                        $i++;
                    }else{
                        break;
                    }
                }

                $category = ProductCategory::findOrFail($tmp_id);
                $category->rank = $rank;
                $category->order = $order_counter;

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Looks for unequal rank by moving reverse.
                 */
                if(count($categories)==0){
                    $category->parent_category_uid = '0';
                }
                for($j=count($categories)-1;$j>=0;$j--){
                    if($rank == 1){ //If rank equals to 1 it means higher rank which parent_menu_uid equals to 0.
                        $category->parent_category_uid = '0';
                        break;
                    }else{
                        if($categories[$j]->rank > $rank){  // If previous one is bigger, searches for first menu that smaller than this menu's rank.
                            for($z=$j;$z>=0;$z--){
                                if($categories[$j]->rank < $rank){
                                    $category->parent_category_uid = $categories[$j]->uid;
                                    break;
                                }
                            }
                            break;
                        }else if($categories[$j]->rank < $rank){    // If previous one is smaller, connects it.
                            $category->parent_category_uid = $categories[$j]->uid;
                            break;
                        }

                    }
                }
                $category->save();
                array_push($categories,$category);
                $order_counter++;
            }
        }
        return true;
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
        $category = ProductCategory::findOrFail($id);

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
                $imageManager = ImageManager::where('type', 'ürün_kategori_içerik_resim_geniş')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($category->name.'-l', $request->cropped_data, 'images', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old files if there is.
                 */
                if (file_exists($category->image_large_screen)) {
                    unlink($category->image_large_screen);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $category->image_large_screen = $result;
                if ($category->save()) {
                    $request->session()->flash('success', 'Kategori Güncellendi');
                } else {
                    $request->session()->flash('error', 'Kategori Güncellenemedi');
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
                $imageManager = ImageManager::where('type', 'ürün_kategori_içerik_resim_mobil')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($category->name.'-m', $request->cropped_data_mobile, 'images', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old files if there is.
                 */
                if (file_exists($category->image_small_screen)) {
                    unlink($category->image_small_screen);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $category->image_small_screen = $result;
                if ($category->save()) {
                    $request->session()->flash('success', 'Kategori Güncellendi');
                } else {
                    $request->session()->flash('error', 'Kategori Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_small');                 //Which tab will be opened.
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
                $imageManager = ImageManager::where('type', 'ürün_kategori_içerik_banner')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($category->name.'-b', $request->cropped_data_banner, 'hero', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old files if there is.
                 */
                if (file_exists($category->hero)) {
                    unlink($category->hero);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $category->hero = $result;
                if ($category->save()) {
                    $request->session()->flash('success', 'Kategori Güncellendi');
                } else {
                    $request->session()->flash('error', 'Kategori Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_banner');                 //Which tab will be opened.
            return back();
        }else if($request->has('updatecategorysettings')){
            $category->name = $request->name;
            $category->keywords = $request->keywords;
            $category->description = $request->description;

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Updating slug with old slug number.
             */
            $category->slug = Str::slug($request->name.'-'.last(explode('-',$category->slug)));

            if ('on' == $request->is_active) {
                $category->is_active = 1;
            } else {
                $category->is_active = 0;
            }
            if ($category->save()) {
                $request->session()->flash('success', 'Başarılı');
            } else {
                $request->session()->flash('error', 'Başarısız');
            }
            session()->flash('tab_page','context');                 //Which tab will be opened.
            return back();
        }else if($request->has('updatecategoryproducts')){

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Returns locations of announcement's on order and re-assigns orders in order to that locations.
             */
            $orders = explode(',',$request->result_h_flow);
            $pivots = ProductCategoryPivot::where('category_id',$request->category_id)->orderBy('product_order','asc')->get();
            $i=0;
            foreach($orders as $order){
                $pivots[$order]->product_order = $i+1;
                $pivots[$order]->save();
                $i++;
            }

            $request->session()->flash('success', 'Başarılı');
            session()->flash('tab_page','order');                 //Which tab will be opened.
            return back();
        }else if($request->has('updatecategoryorder')){//From category update order, others from category update page.
            $result = $this->saveDataRankToMultiLevelNested($request->nestable_output);
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
        $category = ProductCategory::findOrFail($id);
        $lang_code = $category->lang_code;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Get other categories by uid on other languages.
         */
        $category_ids = [];
        foreach(ProductCategory::where('uid',$category->uid)->get() as $c){
            array_push($category_ids,$c->id);
        }

        /**
         * Re-assigning products categories.
         * Eğer ürünün silinen kategorisinin üst kategorisi varsa onu kategori ata
         * Eğer ürünün silinen kategorisinin üst kategori uid si 0 ise;
         *  Ürünün başka kategorisi varsa atama yapma,
         *  Ürünün başka kategorisi yoksa boşta kalır
         *
         */

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Delete other categories on other languages on pivots.
         */
        foreach($category_ids as $category_id){
            foreach (ProductCategoryPivot::where('category_id', $category_id)->get() as $pivot) {
                $pivot->delete();
            }
        }
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Re-order after deleting process.
         * If a category's parent category has deleted, connects that categories parent.
         */
        foreach(ProductCategory::where('parent_category_uid',$category->uid)->where('lang_code',$category->lang_code)->get() as $c){
            $c->parent_category_uid = $category->parent_category_uid;
            $c->save();
        }
        foreach (ProductCategory::where('uid', $category->uid)->get() as $item) {
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Call categories that has bigger order than deleted and reduce their order by 1.
             */
            foreach (ProductCategory::where('lang_code', $item->lang_code)->where('order', '>', $item->order)->get() as $o_item) {
                $o_item->order = $o_item->order - 1;
                $o_item->save();
            }
            $item->delete();
        }
        session()->flash('success', 'Deleted');
        return redirect()->route('admin.products-category-settings.index', ['lang_code' => $lang_code]);
    }
}
