<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Language;
use App\Models\Menu;
use App\Models\ImageManager;
use App\Services\FileManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class ContactSettingsController extends Controller
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

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Works with lang_code parameter;
         * if null default is "tr"
         * if not null and no content on that language routes to "add" page.
         * if not null and there is content for that language, routes to edit page with lang_code, content, langs, page, image sizes parameters.
         */
        if (0 == Contact::all()->count()) {
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * route to "add" page
             */
            return view('admin.contact-settings-add', ['lang_code' => session('lang_code'), 'langs' => Language::all()]); //Parameter came from nav link
        } else {
            $contact = Contact::where('lang_code', $request->lang_code)->get();
            if (0 == $contact->count()) {
                return view('admin.contact-settings-add', ['lang_code' => $request->lang_code, 'langs' => Language::all()]);
            }
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * route to edit page with paremeters
             */
            return view('admin.contact-settings-update', [
                'lang_code' => $request->lang_code,
                'content' => $contact[0],
                'langs' => Language::orderBy('order','asc')->get(),
                'page' => $request->page,
                'large_size' => ImageManager::where('type','iletişim_resim_geniş')->first(),
                'mobile_size' => ImageManager::where('type','iletişim_resim_mobil')->first(),
                'banner_size' => ImageManager::where('type','iletişim_banner')->first(),
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

        $contact = new Contact();
        $contact->title = $request->title;
        $contact->menu_name = $request->menu_name;
        $contact->subtitle = $request->subtitle;
        $contact->keywords = $request->keywords;
        $contact->description = $request->description;
        $contact->short_description = $request->short_description;
        $contact->email = $request->email;
        $contact->phone1 = $request->phone1;
        $contact->phone2 = $request->phone2;
        $contact->gsm1 = $request->gsm1;
        $contact->gsm2 = $request->gsm2;
        $contact->video_embed_code = $request->video_embed_code;
        $contact->fax = $request->fax;
        $contact->city = $request->city;
        $contact->address = $request->address;
        $contact->google_maps = $request->google_maps;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * On same content but different languages has same "menu_code" for matching.
         * UID for just one menu and one content.
         */
        $contact->lang_code = $request->lang_code;
        $contact->menu_code = 'contact';
        $contact->uid = $uniqueid;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * SLUG generating.
         */
        $contact->slug = Str::slug($request->title);

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Save data and if it is success,
         * Create a menu with same data;
         * Same UID, menu_name, lang_code, menu_code,
         */
        if ($contact->save()) {
            $menu = new Menu();
            $menu->uid = $uniqueid;
            $menu->order = (Menu::where('lang_code', $request->lang_code)->get()->count()) + 1;
            $menu->menu_name = $request->menu_name;
            $menu->lang_code = $request->lang_code;
            $menu->menu_code = 'contact';
            $menu->content_slug = $contact->slug;
            $menu->content_id = $contact->id;
            $menu->save();
            $request->session()->flash('success', 'Kayıt Başarılı');
            return redirect()->route('admin.contact-settings.index', ['lang_code' => $request->lang_code])->withInput();
        } else {
            $request->session()->flash('error', 'Kaydedilemedi.');
            return redirect()->route('admin.contact-settings.index', ['lang_code' => $request->lang_code])->withInput();
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
        //
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
        $contact = Contact::findOrFail($id);

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Four different forms.
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
                $imageManager = ImageManager::where('type', 'iletişim_resim_geniş')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($contact->title.'-l',$request->cropped_data,'images',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is one.
                 */
                if (file_exists($contact->image_large_screen)) {
                    unlink($contact->image_large_screen);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $contact->image_large_screen = $result;
                if ($contact->save()) {
                    $request->session()->flash('success', 'İletişim Güncellendi');
                } else {
                    $request->session()->flash('error', 'İletişim Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_large');    //Which tab will be opened.
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
                $imageManager = ImageManager::where('type', 'iletişim_resim_mobil')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($contact->title.'-m',$request->cropped_data_mobile,'images',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is one.
                 */
                if (file_exists($contact->image_small_screen)) {
                    unlink($contact->image_small_screen);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $contact->image_small_screen = $result;
                if ($contact->save()) {
                    $request->session()->flash('success', 'İletişim Güncellendi');
                } else {
                    $request->session()->flash('error', 'İletişim Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_small');    //Which tab will be opened.
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
                $imageManager = ImageManager::where('type', 'iletişim_banner')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($contact->title.'-b',$request->cropped_data_banner,'hero',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is one.
                 */
                if (file_exists($contact->hero)) {
                    unlink($contact->hero);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $contact->hero = $result;
                if ($contact->save()) {
                    $request->session()->flash('success', 'İletişim Güncellendi');
                } else {
                    $request->session()->flash('error', 'İletişim Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_banner');    //Which tab will be opened.
            return back();
        }else if($request->has('updatecontact')){

            $contact->title = $request->title;
            $contact->menu_name = $request->menu_name;
            $contact->subtitle = $request->subtitle;
            $contact->keywords = $request->keywords;
            $contact->description = $request->description;
            $contact->short_description = $request->short_description;
            $contact->email = $request->email;
            $contact->phone1 = $request->phone1;
            $contact->phone2 = $request->phone2;
            $contact->gsm1 = $request->gsm1;
            $contact->gsm2 = $request->gsm2;
            $contact->video_embed_code = $request->video_embed_code;
            $contact->fax = $request->fax;
            $contact->city = $request->city;
            $contact->address = $request->address;
            $contact->google_maps = $request->google_maps;
            $contact->slug = Str::slug($request->title);

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Switch tool returns 'on' or null.
             */
            if ('on' == $request->is_desktop_active) {
                $contact->is_desktop_active = 1;
            } else {
                $contact->is_desktop_active = 0;
            }
            if ('on' == $request->is_mobile_active) {
                $contact->is_mobile_active = 1;
            } else {
                $contact->is_mobile_active = 0;
            }

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Save it and update the menu that has same UID.
             */
            if ($contact->save()) {
                $menu = Menu::where('uid', $contact->uid)->get();
                $menu[0]->menu_name = $request->menu_name;
                $menu[0]->content_slug = $contact->slug;

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * If the content is passive than menu will be passive too but if menu got passive, content will not.
                 */
                if (0 == $contact->is_desktop_active) {
                    $menu[0]->is_desktop_active = 0;
                }else{
                    $menu[0]->is_desktop_active = 1;
                }
                if (0 == $contact->is_mobile_active) {
                    $menu[0]->is_mobile_active = 0;
                }else{
                    $menu[0]->is_mobile_active = 1;
                }
                $menu[0]->save();
                $request->session()->flash('success', 'Kayıt Başarılı');
            } else {
                $request->session()->flash('error', 'Kaydedilemedi.');
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
