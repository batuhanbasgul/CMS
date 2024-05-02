<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Menu;
use App\Models\AboutUs;
use App\Models\AnnouncementInfo;
use App\Models\GalleryInfo;
use App\Models\Contact;
use App\Models\Page;
use App\Models\ProductsInfo;
use App\Models\ReferenceInfo;
use App\Models\ImageManager;
use App\Services\FileManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class MenuController extends Controller
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
         * If request has refresh, refresh the page by just returning.
         */
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
         * If not null fetch that language's menus. Route to settings page with $langs, $menus, $lang_code parameters.
         * If null fetch default language's menus. Route to settings page with $langs, $menus, $lang_code parameters.
         * Active and Passive selection for to show which menus will be showned.
         */
        if($request->show){
            if($request->show=='passive'){
                $is_active = 0;
            }else{
                $is_active = 1;
            }
        }else{
            $is_active = 1;
        }

        if ($request->lang_code) {
            return view('admin.menu-settings', [
                'menus' => Menu::where('lang_code', $request->lang_code)->where('is_mobile_active',$is_active)->where('is_desktop_active',$is_active)->orderBy('order','asc')->get(),
                'langs' => Language::orderBy('order','asc')->get(),
                'lang_code' => $request->lang_code,
                'show' => $request->show
            ]);
        } else {
            return view('admin.menu-settings', [
                'menus' => Menu::where('lang_code', session('lang_code'))->where('is_mobile_active',$is_active)->where('is_desktop_active',$is_active)->orderBy('order','asc')->get(),
                'langs' => Language::orderBy('order','asc')->get(),
                'lang_code' => session('lang_code'),
                'show' => $request->show
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

    /** ►►►►► DEVELOPER ◄◄◄◄◄
     * Order management.
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

        $menus = Menu::where('lang_code', $request->lang_code)->orderBy('order','asc')->get();
        if(count($menus) == 0){
            $request->session()->flash('no_menu','Kategori Kaydı Bulunamadı');
            return back();
        }
        return view('admin.menu-settings-update-order', [
            'menus' => $menus,
            'langs' => Language::orderBy('order','asc')->get(),
            'lang_code' => $request->lang_code,
        ]);
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

        $menu = Menu::findOrFail($id);
        if($request->show == 'active'){
            $menus = Menu::where('lang_code', $menu->lang_code)->where('is_desktop_active',1)->orWhere('is_mobile_active',1)->orderBy('order','asc')->get();
            return view('admin.menu-settings-update', [
                'menu' => $menu,
                'menus' => $menus,
                'page' => $request->page,
                'image_size' => ImageManager::where('type','menü_icon')->first(),
                'show' => $request->show,
                'langs' => Language::orderBy('order','asc')->get(),
                'lang_code' => $request->lang_code,
            ]);
        }else{
            $menus = Menu::where('lang_code', $menu->lang_code)->where('is_desktop_active',0)->orWhere('is_mobile_active',0)->orderBy('order','asc')->get();
            return view('admin.menu-settings-update', [
                'menu' => $menu,
                'menus' => $menus,
                'page' => $request->page,
                'image_size' => ImageManager::where('type','menü_icon')->first(),
                'show' => $request->show,
                'langs' => Language::orderBy('order','asc')->get(),
                'lang_code' => $request->lang_code,
            ]);
        }
    }

    /** ►►►►► DEVELOPER ◄◄◄◄◄
     * ID array from string data.
     * Parent Menu UIDs are connected to parent child relation with rank of menu in nested structure.
     * UIDs are unique for one menu and one content, menu_code for content type and same for every same content even on languages.
     */
    public function saveDataRankToMultiLevelNested($data_string){
        $rank = 0;  //Rank ***
        $order_counter = 1; //General ordering, defines all order struct.
        $menus = [];
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

                $menu = Menu::findOrFail($tmp_id);
                $menu->rank = $rank;
                $menu->order = $order_counter;

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Looks for unequal rank by moving reverse.
                 */
                if(count($menus)==0){
                    $menu->parent_menu_uid = '0';
                }
                for($j=count($menus)-1;$j>=0;$j--){
                    if($rank == 1){ //If rank equals to 1 it means higher rank which parent_menu_uid equals to 0.
                        $menu->parent_menu_uid = '0';
                        break;
                    }else{
                        if($menus[$j]->rank > $rank){  // If previous one is bigger, searches for first menu that smaller than this menu's rank.
                            for($z=$j;$z>=0;$z--){
                                if($menus[$j]->rank < $rank){
                                    $menu->parent_menu_uid = $menus[$j]->uid;
                                    break;
                                }
                            }
                            break;
                        }else if($menus[$j]->rank < $rank){    // If previous one is smaller, connects it.
                            $menu->parent_menu_uid = $menus[$j]->uid;
                            break;
                        }

                    }
                }
                $menu->save();
                array_push($menus,$menu);
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
        $menu = Menu::findOrFail($id);
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Three different forms.
         */
        if($request->has('updatelogo')){
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Validation for file mime from FileManagerService.
             */
            if($request->hasFile('menu_logo')){
                if(!$this->fileManagerService->checkExtension($request->menu_logo)){
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
                $imageManager = ImageManager::where('type', 'menü_icon')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($menu->menu_name,$request->cropped_data_favicon,'logos',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is one.
                 */
                if (file_exists($menu->menu_logo)) {
                    unlink($menu->menu_logo);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $menu->menu_logo = $result;
                if ($menu->save()) {
                    $request->session()->flash('success', 'Menu Güncellendi');
                } else {
                    $request->session()->flash('error', 'Menu Güncellenemedi');
                }
            }
            session()->flash('tab_page','logo');    //Which tab will be opened.
            return back();
        }else if($request->has('updatemenu')){
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Switch tool returns 'on' or null.
             */
            if ('on' == $request->is_desktop_active) {
                $menu->is_desktop_active = 1;
            } else {
                $menu->is_desktop_active = 0;
            }
            if ('on' == $request->is_mobile_active) {
                $menu->is_mobile_active = 1;
            } else {
                $menu->is_mobile_active = 0;
            }
            $menu->menu_name = $request->menu_name;

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Calls menu's content in order to menu_code and lang_code.
             */
            if($menu->menu_code == 'page'){
                $content = Page::where('lang_code',$menu->lang_code)->where('menu_name',$menu->menu_name)->get();
            }else if($menu->menu_code == 'about_us'){
                $content = AboutUs::where('lang_code',$menu->lang_code)->get();
            }else if($menu->menu_code == 'references'){
                $content = ReferenceInfo::where('lang_code',$menu->lang_code)->get();
            }else if($menu->menu_code == 'gallery_info'){
                $content = GalleryInfo::where('lang_code',$menu->lang_code)->get();
            }else if($menu->menu_code == 'products_info'){
                $content = ProductsInfo::where('lang_code',$menu->lang_code)->get();
            }else if($menu->menu_code == 'announcement'){
                $content = AnnouncementInfo::where('lang_code',$menu->lang_code)->get();
            }else if($menu->menu_code == 'contact'){
                $content = Contact::where('lang_code',$menu->lang_code)->get();
            }else{
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * If it is for index, finish and return.
                 * Else manage activity and give parameter to content and menu with menu_code.
                 */
                if ($menu->save()) {
                    $request->session()->flash('success', 'Menu Güncellendi.');
                } else {
                    $request->session()->flash('error', 'Menu Güncellenemedi.');
                }
                session()->flash('tab_page','context');    //Which tab will be opened.
                return back();
            }

            if($request->is_desktop_active){
                $content[0]->is_desktop_active = 1;
            }
            if($request->is_mobile_active){
                $content[0]->is_mobile_active = 1;
            }
            $content[0]->menu_name = $request->menu_name;
            $content[0]->save();


            if ($menu->save()) {
                $request->session()->flash('success', 'Menu Güncellendi.');
            } else {
                $request->session()->flash('error', 'Menu Güncellenemedi.');
            }
            session()->flash('tab_page','context');    //Which tab will be opened.
            return back();
        }else if($request->has('updatemenuorder')){
            $request->session()->flash('success', 'Menu Güncellendi.');
            $result = $this->saveDataRankToMultiLevelNested($request->nestable_output);
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
