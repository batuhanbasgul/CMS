<?php

namespace App\Http\Controllers;

use App\Models\Construction;
use App\Models\AppMaintenanceModel;
use App\Models\PanelMaintenanceModel;
use App\Models\Header;
use App\Models\Language;
use App\Models\Mail;
use App\Models\Setting;
use App\Models\User;
use App\Models\View;
use App\Models\AppSettings;
use App\Models\ImageManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;


class AdminPanelController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $user = User::findOrFail(Auth::id());
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Sidebar selected item
         */
        session()->forget('selectedSideMenu');
        session()->put('selectedSideMenu',last(explode('/',URL::current())));

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * User Preferences Sessions for theme,
         * Inside User data table on database
         */
        session()->forget('theme_dark');
        session()->put('theme_dark', $user->theme_dark);

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
         * User Info Sessions
         * Inside User data table on database
         */
        session()->put('profile_image', $user->profile_image);
        session()->put('user_name', $user->name);
        session()->put('user_email', $user->email);

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Maintenance notification
         */

        $setting = Setting::first();
        if(Construction::where('lang_code',session('lang_code'))->first()){
            $construction = Construction::where('lang_code',session('lang_code'))->first();
        }else{
            $construction = Construction::first();
        }
        if(AppMaintenanceModel::where('lang_code',session('lang_code'))->first()){
            $appMaintenance = AppMaintenanceModel::where('lang_code',session('lang_code'))->first();
        }else{
            $appMaintenance = AppMaintenanceModel::first();
        }
        if(PanelMaintenanceModel::where('lang_code',session('lang_code'))->first()){
            $panelMaintenance = PanelMaintenanceModel::where('lang_code',session('lang_code'))->first();
        }else{
            $panelMaintenance = PanelMaintenanceModel::first();
        }
        if($setting->maintenance_app || $setting->maintenance_panel){
            session()->put('maintenance', true);
        }else{
            session()->put('maintenance', false);
        }
        if($construction){
            session()->put('construction', $construction->is_active);
        }
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Show View Counter
         * Inside Views data table on database
         */
        $viewCount = [
            'daily' => View::where('created_at', '>', Carbon::yesterday())->get()->count(),
            'weekly' => View::where('created_at', '>', Carbon::now()->subWeek())->get()->count(),
            'monthly' => View::where('created_at', '>', Carbon::now()->subMonth())->get()->count(),
            'sum' => View::all()->count(),
        ];

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Checking Languages
         * If there is no language, generate first language on first opening.
         */
        $langControl = Language::where('lang_code', session('lang_code'))->get();
        if (0 == $langControl->count()) {
            $lang = new Language();
            $lang->lang_code = session('lang_code');
            $lang->order = 1;
            $lang->is_default = 1;
            $lang->save();
        }

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Icon
         * Fetch logo from Header, use small firstly.
         */
        if (!empty(Header::first()->logo_small_screen)) {
            session()->put('icon', Header::first()->logo_small_screen);
        } else if (!empty(Header::first()->logo_large_screen)) {
            session()->put('icon', Header::first()->logo_large_screen);
        }

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Fav_Icon from AppSettings
         */
        if (!empty(AppSettings::first())) {
            session()->forget('fav-icon');
            session()->put('fav-icon', AppSettings::first()->fav_icon);
        }

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Mail Notification
         * Create session for unread mails.
         * Save unread mails to $unread_mails by limiting it to 5.
         */
        $unread_mails = Mail::where('is_read', 0)->orderBy('created_at', 'desc')->limit('10')->get();
        if (0 < Mail::where('is_read', 0)->orderBy('created_at', 'desc')->limit('10')->get()->count()) {
            session()->put('unread_mails', Mail::where('is_read', 0)->orderBy('created_at', 'desc')->get());
        }else{
            session()->forget('unread_mails');
        }

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * To generate it on first opening.
         * Create image settings table for Master settings.
         */
        if(ImageManager::all()->count() < 1){
            $types = [
                'kullanıcı_resim',
                'website_favicon',
                'website_banner',
                'popup_resim_geniş',
                'popup_resim_mobil',
                'dil_icon',
                'header_resim_geniş',
                'header_resim_mobil',
                'footer_resim_geniş',
                'footer_resim_mobil',
                'menü_icon',
                'slider_resim_geniş',
                'slider_resim_mobil',
                'hakkımızda_resim_geniş',
                'hakkımızda_resim_mobil',
                'hakkımızda_banner',
                'referans_içerik_resim_geniş',
                'referans_içerik_resim_mobil',
                'referans_içerik_banner',
                'referans_resim_geniş',
                'referans_resim_mobil',
                'referans_thumbnail_geniş',
                'referans_thumbnail_mobil',
                'ucret_içerik_resim_geniş',
                'ucret_içerik_resim_mobil',
                'ucret_içerik_banner',
                'ucret_resim_geniş',
                'ucret_resim_mobil',
                'ucret_thumbnail_geniş',
                'ucret_thumbnail_mobil',
                'galeri_içerik_resim_geniş',
                'galeri_içerik_resim_mobil',
                'galeri_içerik_banner',
                'galeri_resim',
                'galeri_thumbnail',
                'ürün_içerik_resim_geniş',
                'ürün_içerik_resim_mobil',
                'ürün_içerik_banner',
                'ürün_kategori_içerik_resim_geniş',
                'ürün_kategori_içerik_resim_mobil',
                'ürün_kategori_içerik_banner',
                'ürün_resim_geniş',
                'ürün_resim_mobil',
                'ürün_thumbnail_geniş',
                'ürün_thumbnail_mobil',
                'ürün_thumbnail2_geniş',
                'ürün_thumbnail2_mobil',
                'ürün_banner',
                'ürün_resim',
                'duyuru_içerik_resim_geniş',
                'duyuru_içerik_resim_mobil',
                'duyuru_içerik_banner',
                'duyuru_resim_geniş',
                'duyuru_resim_mobil',
                'duyuru_thumbnail_geniş',
                'duyuru_thumbnail_mobil',
                'duyuru_banner',
                'duyuru_resim',
                'iletişim_resim_geniş',
                'iletişim_resim_mobil',
                'iletişim_banner',
                'yapım_aşaması',
                'front_bakim_aşaması',
                'back_bakim_aşaması',
                'sayfa_resim_geniş',
                'sayfa_resim_mobil',
                'sayfa_banner',
                'sayfa_resim',
                'sayfa_thumbnail_geniş',
                'sayfa_thumbnail_mobil',
                'hakkimizda_kart_resim_geniş',
                'hakkimizda_kart_resim_mobil',
                'hakkimizda_kart_thumbnail_geniş',
                'hakkimizda_kart_thumbnail_mobil',
            ];
            foreach($types as $type){
                $imageManager = new ImageManager();
                $imageManager->type = $type;
                $imageManager->save();
            }
        }


        if($request->refresh){
            if(session('error')){
                $request->session()->flash('error','Hata');
            }
            if(session('success')){
                $request->session()->flash('success','İşlem Başarılı.');
            }
            return back();
        }
        if($request->mail_refresh){
            return view('admin.mail-box-show', ['mail' => Mail::findOrFail($request->mail)]);
        }
        /**
         * mails -> Unread 5 mails.
         * views -> Viewing count.
         * maintenance -> Maintenance of admin panel.
         * construction -> Construction of app.
         * user_name ->
         * Routes to Admin Panel Index page.
         */
        return view('admin.index', [
            'mails' => $unread_mails,
            'views' => $viewCount,
            'maintenance' => Setting::first(),
            'construction' => $construction,
            'app_maintenance' => $appMaintenance,
            'panel_maintenance' => $panelMaintenance,
            'user_name' => $user->name
        ]);
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
        //
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
    /** ►►►►► DEVELOPER ◄◄◄◄◄
     * Changing language method.
     */
    public function changeLocale($lang_code){
        session()->forget('lang_code');
        session()->put('lang_code', $lang_code);
        session()->flash('lang_code_changed', true);
        App::setLocale($lang_code);
        return redirect()->back();
    }
}
