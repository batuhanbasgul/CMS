<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Menu;
use App\Models\ProductsInfo;
use App\Models\ImageManager;
use App\Services\FileManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class ProductsInfoSettingsController extends Controller
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
        /**
         * Validation about no info from categories
         */
        if($request->no_info){
            $request->session()->flash('no_products_page_info');
        }
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Works with lang_code parameter;
         * if null default is "tr"
         * if not null and no content on that language routes to "add" page.
         * if not null and there is content for that language, reroutes to edit page with lang_code, announcementInfo, langs, image sizes parameters.
         */

        if (0 == ProductsInfo::all()->count()) {
            return view('admin.products-info-settings-add', ['lang_code' => session('lang_code'), 'langs' => Language::all()]); //Parameter comes from nav manu
        } else {
            $productsInfo = ProductsInfo::where('lang_code', $request->lang_code)->get();
            if (0 == $productsInfo->count()) {
                return view('admin.products-info-settings-add', ['lang_code' => $request->lang_code, 'langs' => Language::all()]);
            }
            return view('admin.products-info-settings-update', [
                'lang_code' => $request->lang_code,
                'content' => $productsInfo[0],
                'langs' => Language::orderBy('order','asc')->get(),
                'large_size' => ImageManager::where('type','ürün_içerik_resim_geniş')->first(),
                'mobile_size' => ImageManager::where('type','ürün_içerik_resim_mobil')->first(),
                'banner_size' => ImageManager::where('type','ürün_içerik_banner')->first()
            ]);
        }
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

        $productsInfo = new ProductsInfo();
        $productsInfo->uid = $uniqueid;
        $productsInfo->title = $request->title;
        $productsInfo->subtitle = $request->subtitle;
        $productsInfo->keywords = $request->keywords;
        $productsInfo->description = $request->description;
        $productsInfo->short_description = $request->short_description;
        $productsInfo->video_embed_code = $request->video_embed_code;
        $productsInfo->menu_name = $request->menu_name;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Page limit to show on website.
         */
        if($request->page_limit){
            $productsInfo->page_limit = $request->page_limit;
        }else{
            $productsInfo->page_limit = 12;
        }

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * On same content but different languages has same "menu_code" for matching.
         * UID for just one menu and one content.
         */
        $productsInfo->lang_code = $request->lang_code;
        $productsInfo->menu_code = 'products_info';

        /** ►►►►► DEVELOPER ◄◄◄◄◄
        * SLUG generating.
         */
        $productsInfo->slug = Str::slug($request->title);

        /** ►►►►► DEVELOPER ◄◄◄◄◄
        * Save data and if it is success,
        * Create a menu with same data;
        * Same UID, menu_name, lang_code, menu_code,
         */
        if ($productsInfo->save()) {
            $menu = new Menu();
            $menu->uid = $uniqueid;
            $menu->order = (Menu::where('lang_code', $request->lang_code)->get()->count()) + 1;
            $menu->menu_name = $request->menu_name;
            $menu->lang_code = $request->lang_code;
            $menu->menu_code = 'products_info';
            $menu->content_slug = $productsInfo->slug;
            $menu->content_id = $productsInfo->id;
            $menu->save();

            $request->session()->flash('success', 'Kayıt Başarılı');
        } else {
            $request->session()->flash('error', 'Kayıt Başarısız');
        }
        return redirect()->route('admin.products-info-settings.index', ['lang_code' => $request->lang_code])->withInput();
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
     * Unique dosya ismi üret
     */
    public function generateUniqueFileName($extension)
    {
        return uniqid() . '.' . $extension;
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
        $productsInfo = ProductsInfo::findOrFail($id);
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
                $imageManager = ImageManager::where('type', 'ürün_içerik_resim_geniş')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($productsInfo->title.'-l', $request->cropped_data, 'images', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is one.
                 */
                if (file_exists($productsInfo->image_large_screen)) {
                    unlink($productsInfo->image_large_screen);
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $productsInfo->image_large_screen = $result;
                if ($productsInfo->save()) {
                    $request->session()->flash('success', 'Ürünler Sayfası Güncellendi');
                } else {
                    $request->session()->flash('error', 'Ürünler Sayfası Güncellenemedi');
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
                $imageManager = ImageManager::where('type', 'ürün_içerik_resim_mobil')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($productsInfo->title.'-m', $request->cropped_data_mobile, 'images', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is one.
                 */
                if (file_exists($productsInfo->image_small_screen)) {
                    unlink($productsInfo->image_small_screen);
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $productsInfo->image_small_screen = $result;
                if ($productsInfo->save()) {
                    $request->session()->flash('success', 'Ürünler Sayfası Güncellendi');
                } else {
                    $request->session()->flash('error', 'Ürünler Sayfası Güncellenemedi');
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
                $imageManager = ImageManager::where('type', 'ürün_içerik_banner')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($productsInfo->title.'-b', $request->cropped_data_banner, 'hero', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is one.
                 */
                if (file_exists($productsInfo->hero)) {
                    unlink($productsInfo->hero);
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $productsInfo->hero = $result;
                if ($productsInfo->save()) {
                    $request->session()->flash('success', 'Hakkımızda Güncellendi');
                } else {
                    $request->session()->flash('error', 'Hakkımızda Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_banner');    //Which tab will be opened.
            return back();
        } else if ($request->has('updateproductsinfo')) {

            $productsInfo->title = $request->title;
            $productsInfo->subtitle = $request->subtitle;
            $productsInfo->description = $request->description;
            $productsInfo->short_description = $request->short_description;
            $productsInfo->keywords = $request->keywords;
            $productsInfo->video_embed_code = $request->video_embed_code;
            $productsInfo->menu_name = $request->menu_name;

            if($request->page_limit){
                $productsInfo->page_limit = $request->page_limit;
            }else{
                $productsInfo->page_limit = 12;
            }

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Updating slug
             */
            $productsInfo->slug = Str::slug($request->title);

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Switch tool returns 'on' or null.
             */
            if ('on' == $request->is_desktop_active) {
                $productsInfo->is_desktop_active = 1;
            } else {
                $productsInfo->is_desktop_active = 0;
            }
            if ('on' == $request->is_mobile_active) {
                $productsInfo->is_mobile_active = 1;
            } else {
                $productsInfo->is_mobile_active = 0;
            }

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Save it and update the menu that has same UID.
             */
            if ($productsInfo->save()) {
                $menu = Menu::where('uid', $productsInfo->uid)->get();
                $menu[0]->menu_name = $request->menu_name;
                $menu[0]->content_slug = $productsInfo->slug;

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * If the content is passive than menu will be passive too but if menu got passive, content will not.
                 */
                if (0 == $productsInfo->is_desktop_active) {
                    $menu[0]->is_desktop_active = 0;
                }else{
                    $menu[0]->is_desktop_active = 1;
                }
                if (0 == $productsInfo->is_mobile_active) {
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
