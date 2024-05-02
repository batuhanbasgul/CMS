<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Models\AboutUs;
use App\Models\AboutUsCards;
use App\Models\AppSettings;
use App\Models\Contact;
use App\Models\Footer;
use App\Models\Gallery;
use App\Models\GalleryInfo;
use App\Models\Header;
use App\Models\ImageManager;
use App\Models\Language;
use App\Models\Menu;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductCategoryPivot;
use App\Models\ProductImage;
use App\Models\ProductsInfo;
use App\Models\Reference;
use App\Models\PricingInfo;
use App\Models\Pricing;
use App\Models\Slider;
use App\Models\View;
use App\Models\AppCard;
use App\Models\AppBlog;
use App\Models\AppReference;
use App\Models\Constant;
use App\Models\Announcement;
use Illuminate\Support\Facades\URL;
/*
use App\Models\AnnouncementImage;
use App\Models\AnnouncementInfo;
use App\Models\Page;
use App\Models\PageImage;
use App\Models\ReferenceInfo;
*/

//use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /** ►►►►► DEVELOPER ◄◄◄◄◄
     * Changing language method.
     */
    public function changeLocale($lang_code){
        session()->forget('locale');
        session()->put('locale', $lang_code);
        session()->flash('langChanged', true);
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * If URL's last path is empty or lang code route to homepage or return.
         */
        $lang_codes = [];
        foreach(Language::where('is_active',1)->get() as $l){
            array_push($lang_codes, $l->lang_code);
        }
        if(!last(explode('/',URL::previous()))){
            return redirect()->route('slug',$lang_code);
        }else if(in_array(last(explode('/',URL::previous())), $lang_codes)){
            return redirect()->route('slug',$lang_code);
        }else{
            return back();
        }
    }

    public function setLocaleSession(){
        $browserLocale = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        $availableLangs = [];
        foreach(Language::where('is_active',1)->get() as $lang){
            array_push($availableLangs,$lang->lang_code);
        }

        if(in_array($browserLocale,$availableLangs)){
            session()->put('locale', $browserLocale);
        }else{
            $dbLangCode = Language::where('is_default',1)->first()->lang_code;
            if($dbLangCode){
                session()->put('locale','tr');
            }else{
                session()->put('locale', Language::where('is_default',1)->first()->lang_code);
            }
        }

    }

    public function homePage(){
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Assigning Locale Language
         */
        if(session('locale') == null){
            $this->setLocaleSession();
        }
        $locale = session('locale',Language::where('is_default',1)->first()->lang_code);

        if (null == session('viewed')) {
            View::create();
            session()->put('viewed', 'viewed');
        }

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Homepage Menus.
         * Index tab creation.
         */
        if(0 == count(Menu::where('lang_code',$locale)->where('content_id',0)->get())){
            $index = new Menu();
            $index->uid = sha1(uniqid());
            $index->order = (Menu::where('lang_code', $locale)->get()->count()) + 1;
            $index->menu_name = 'index';
            $index->lang_code = $locale;
            $index->menu_code = 'index';
            $index->content_slug = '/';
            $index->content_id = 0;
            $index->save();
        }

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * If there is no data on selected language, return back.
         */
        if(null != AppSettings::where('lang_code',$locale)->get()->first()){
            $appSetting = AppSettings::where('lang_code',$locale)->get()->first();
        }else{
            $appSetting = new AppSettings();
        }
        if(null != Header::where('lang_code',$locale)->get()->first()){
            $header = Header::where('lang_code',$locale)->get()->first();
        }else{
            $header = new Header();
        }
        if(null != Footer::where('lang_code',$locale)->get()->first()){
            $footer = Footer::where('lang_code',$locale)->get()->first();
        }else{
            $footer = new Footer();
        }
        if(null != Slider::where('lang_code',$locale)->orderBy('order','asc')->get()){
            $sliders = Slider::where('lang_code',$locale)->orderBy('order','asc')->get();
        }else{
            $sliders = new Slider();
        }
        if(null != Menu::where('lang_code',$locale)->orderBy('order','asc')->get()){
            $menus = Menu::where('lang_code',$locale)->orderBy('order','asc')->get();
        }else{
            $menus = new Menu();
        }
        if(null != AboutUs::where('lang_code',$locale)->get()->first()){
            $aboutUs = AboutUs::where('lang_code',$locale)->get()->first();
        }else{
            $aboutUs = new AboutUs();
        }
        if(null != AboutUsCards::where('lang_code',$locale)->orderBy('order','asc')->get()){
            $aboutUsCards = AboutUsCards::where('lang_code',$locale)->orderBy('order','asc')->get();
        }else{
            $aboutUsCards = new AboutUsCards();
        }
        if(null != Contact::where('lang_code',$locale)->get()->first()){
            $contact = Contact::where('lang_code',$locale)->get()->first();
        }else{
            $contact = new Contact();
        }
        if(null != ProductsInfo::where('lang_code',$locale)->get()->first()){
            $productsInfo = ProductsInfo::where('lang_code',$locale)->get()->first();
        }else{
            $productsInfo = new ProductsInfo();
        }
        if(null != ProductCategory::where('lang_code',$locale)->orderBy('order','asc')->get()){
            $productCategories = ProductCategory::where('lang_code',$locale)->orderBy('order','asc')->get();
        }else{
            $productCategories = new ProductCategory();
        }
        if(null != AppCard::where('lang_code',$locale)->orderBy('order','asc')->get()){
            $appCards = AppCard::where('lang_code',$locale)->orderBy('order','asc')->get();
        }else{
            $appCards = new AppCard();
        }
        if(null != AppBlog::where('lang_code',$locale)->first()){
            $appBlog = AppBlog::where('lang_code',$locale)->first();
        }else{
            $appBlog = new AppBlog();
        }
        if(null != Constant::where('lang_code',$locale)->first()){
            $constant = Constant::where('lang_code',$locale)->first();
        }else{
            $constant = new Constant();
        }
        if(null != AppReference::where('lang_code',$locale)->first()){
            $appReference = AppReference::where('lang_code',$locale)->first();
        }else{
            $appReference = new AppReference();
        }
        if(null != Reference::where('lang_code',$locale)->orderBy('order','asc')->get()){
            if($appReference->reference_count){
                $references = Reference::where('lang_code',$locale)->orderBy('order','asc')->take($appReference->reference_count)->get();
            }else{
                $references = Reference::where('lang_code',$locale)->orderBy('order','asc')->get();
            }
        }else{
            $references = new Reference();
        }
        if(null != PricingInfo::where('lang_code',$locale)->first()){
            $pricingInfo = PricingInfo::where('lang_code',$locale)->first();
        }else{
            $pricingInfo = new PricingInfo();
        }
        if(null != Pricing::where('lang_code',$locale)->orderBy('order','asc')->get()){
            $prices = Pricing::where('lang_code',$locale)->orderBy('order','asc')->get();
        }else{
            $prices = new Pricing();
        }
        $products = Product::where('lang_code',$locale)->where('is_active',1)->orderBy('order','asc')->get();
        $productCount = count(Product::where('lang_code',$locale)->where('is_active',1)->get());
        $pivots = ProductCategoryPivot::orderBy('product_order','asc')->get();
        if(null != Announcement::where('lang_code',$locale)->orderBy('order','asc')->get()){
            $announcements = Announcement::where('lang_code',$locale)->orderBy('order','asc')->get();
        }else{
            $announcements = new Announcement();
        }
        /*
        if(null != AnnouncementInfo::where('lang_code',$locale)->first()){
            $announcementInfo = AnnouncementInfo::where('lang_code',$locale)->first();
        }else{
            $announcementInfo = new AnnouncementInfo();
        }
        if(null != ReferenceInfo::where('lang_code',$locale)->first()){
            $referenceInfo = ReferenceInfo::where('lang_code',$locale)->first();
        }else{
            $referenceInfo = new ReferenceInfo();
        }
        if(null != Announcement::where('lang_code',$locale)->orderBy('announcement_date','asc')->get()){
            if($appBlog->blog_count){
                $announcements = Announcement::where('lang_code',$locale)->orderBy('announcement_date','desc')->take($appBlog->blog_count)->get();
            }else{
                $announcements = Announcement::where('lang_code',$locale)->orderBy('announcement_date','desc')->get();
            }
        }else{
            $announcements = new Announcement();
        }
        */
        if($pricingInfo->slug){
            $pricingInfoSlug = $pricingInfo->slug;
        }else{
            $pricingInfoSlug = '/';
        }
        if($contact->slug){
            $contactSlug = $contact->slug;
        }else{
            $contactSlug = '/';
        }
        return view('index',[
            'appSetting' => $appSetting,
            'header' => $header,
            'footer' => $footer,
            'menus' => $menus,
            'sliders' => $sliders,
            'aboutUs' => $aboutUs,
            'aboutUsCards' => $aboutUsCards,
            'contact' => $contact,
            'contactSlug' => $contactSlug,
            'langs' => Language::orderBy('order','asc')->get(),
            'appCards' => $appCards,
            'appBlog' => $appBlog,
            'appReference' => $appReference,
            'references' => $references,
            'pricingInfo' => $pricingInfo,
            'pricingInfoSlug' => $pricingInfoSlug,
            'prices' => $prices,
            'productsInfo' => $productsInfo,
            'productCategories' => $productCategories,
            'products' => $products,
            'productCount' => $productCount,
            'pivots' => $pivots,
            'slider_large_size' => ImageManager::where('type', 'slider_resim_geniş')->first(),
            'slider_mobile_size' => ImageManager::where('type', 'slider_resim_mobil')->first(),
            'content' => null,
            'constant' => $constant,
            'announcements' => $announcements,
            /*
            'referenceInfo' =>$referenceInfo,
            'announcementInfo' => $announcementInfo,
            */
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return $this->homePage();
    }

    /** ►►►►► DEVELOPER ◄◄◄◄◄
     * Slug methods.
     */
    public function slug( Request $request, $slug, $subSlug = null, $subSubSlug = null, $subSubSubSlug = null, $lastSlug = null)
    {
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Assign languge to variable, if there is no language assign deafult language.
         */
        //Setting Locale Language
        if(session('locale') == null){
            $this->setLocaleSession();
        }
        $locale = session('locale');
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * If there is no content on selected language, assign null.
         */
        if(null != AppSettings::where('lang_code',$locale)->get()->first()){
            $appSetting = AppSettings::where('lang_code',$locale)->get()->first();
        }else{
            $appSetting = new AppSettings();
        }
        if(null != Header::where('lang_code',$locale)->get()->first()){
            $header = Header::where('lang_code',$locale)->get()->first();
        }else{
            $header = new Header();
        }
        if(null != Footer::where('lang_code',$locale)->get()->first()){
            $footer = Footer::where('lang_code',$locale)->get()->first();
        }else{
            $footer = new Footer();
        }
        if(null != Menu::where('lang_code',$locale)->orderBy('order','asc')->get()){
            $menus = Menu::where('lang_code',$locale)->orderBy('order','asc')->get();
        }else{
            $menus = new Menu();
        }
        if(null != Contact::where('lang_code',$locale)->get()->first()){
            $contact = Contact::where('lang_code',$locale)->get()->first();
        }else{
            $contact = new Contact();
        }
        if(null != ProductsInfo::where('lang_code',$locale)->get()->first()){
            $productsInfo = ProductsInfo::where('lang_code',$locale)->get()->first();
        }else{
            $productsInfo = new ProductsInfo();
        }
        if(null != ProductCategory::where('lang_code',$locale)->where('is_active',1)->orderBy('order','asc')->get()){
            $productCategories = ProductCategory::where('lang_code',$locale)->where('is_active',1)->orderBy('order','asc')->get();
        }else{
            $productCategories = new ProductCategory();
        }
        if(null != Constant::where('lang_code',$locale)->first()){
            $constant = Constant::where('lang_code',$locale)->first();
        }else{
            $constant = new Constant();
        }
        if(null != PricingInfo::where('lang_code',$locale)->first()){
            $pricingInfo = PricingInfo::where('lang_code',$locale)->first();
        }else{
            $pricingInfo = new PricingInfo();
        }
        if(null != Pricing::where('lang_code',$locale)->orderBy('order','asc')->get()){
            $prices = Pricing::where('lang_code',$locale)->orderBy('order','asc')->get();
        }else{
            $prices = new Pricing();
        }
        /*
        if(null != AnnouncementInfo::where('lang_code',$locale)->get()->first()){
            $announcementInfo = AnnouncementInfo::where('lang_code',$locale)->get()->first();
        }else{
            $announcementInfo = new AnnouncementInfo();
        }
        */

        if(Menu::where('content_slug',$slug)->first()){
            switch (Menu::where('content_slug',$slug)->first()->menu_code) {
                case 'about_us':
                    if(null != AboutUs::where('lang_code',$locale)->first()){
                        $content = AboutUs::where('lang_code',$locale)->first();
                    }else{
                        $content = new AboutUs();
                    }
                    $aboutusCards = AboutUsCards::where('lang_code',$locale)->orderBy('order','asc')->get();

                    /**
                     * Dil değiştiyse slug değiştir.
                     */
                    if(session('langChanged')){
                        return redirect()->route('slug', [$content->slug]);
                    }
                    /**
                     * Slug diline göre lang_code session tekrar atama
                     */
                    $targetLangCode = Menu::where('content_slug',$slug)->first()->lang_code;
                    if($targetLangCode != session('locale')){
                        session()->put('locale',$targetLangCode);
                        $item = AboutUs::where('lang_code',$targetLangCode)->first();
                        return redirect()->route('slug',[$item->slug]);
                    }
                    /**
                     * Eğer içerik pasifse anasayfa yönlendir.
                     */
                    if(session('is_desktop')){
                        if(!$content->is_desktop_active){
                            return redirect()->route('index');
                        }
                    }else{
                        if(!$content->is_mobile_active){
                            return redirect()->route('index');
                        }
                    }
                    return view('about-us',[
                        'appSetting' => $appSetting,
                        'header' => $header,
                        'footer' => $footer,
                        'menus' => $menus,
                        'productCategories' => $productCategories,
                        'contact' => $contact,
                        'langs' => Language::orderBy('order','asc')->get(),
                        'content' => $content,
                        'aboutusCards' => $aboutusCards,
                        'productsInfo' => $productsInfo,
                        'constant' => $constant,
                        'homePageMenu' => Menu::where('menu_code','index')->where('lang_code',$locale)->first(),
                        'images' => Gallery::where('menu_code','about_us')->where('lang_code',$locale)->orderBy('order','asc')->get()
                    ]);
                    break;
                case 'contact':
                    if(null != Contact::where('lang_code',$locale)->first()){
                        $content = Contact::where('lang_code',$locale)->first();
                    }else{
                        $content = new Contact();
                    }
                    /**
                     * Dil değiştiyse slug değiştir.
                     */
                    if(session('langChanged')){
                        return redirect()->route('slug', [$content->slug]);
                    }
                    /**
                     * Slug diline göre lang_code session tekrar atama
                     */
                    $targetLangCode = Menu::where('content_slug',$slug)->first()->lang_code;
                    if($targetLangCode != session('locale')){
                        session()->put('locale',$targetLangCode);
                        $item = Contact::where('lang_code',$targetLangCode)->first();
                        return redirect()->route('slug',[$item->slug]);
                    }

                    /**
                     * Eğer içerik pasifse anasayfa yönlendir.
                     */
                    if(session('is_desktop')){
                        if(!$content->is_desktop_active){
                            return redirect()->route('index');
                        }
                    }else{
                        if(!$content->is_mobile_active){
                            return redirect()->route('index');
                        }
                    }
                    return view('contact-us',[
                        'appSetting' => $appSetting,
                        'header' => $header,
                        'footer' => $footer,
                        'menus' => $menus,
                        'productCategories' => $productCategories,
                        'contact' => $contact,
                        'langs' => Language::orderBy('order','asc')->get(),
                        'productsInfo' => $productsInfo,
                        'content' => $content,
                        'constant' => $constant,
                        'homePageMenu' => Menu::where('menu_code','index')->where('lang_code',$locale)->first()
                    ]);
                    break;
                /*
                case 'announcement':
                    if(null != AnnouncementInfo::where('lang_code',$locale)->first()){
                        $content = AnnouncementInfo::where('lang_code',$locale)->first();
                    }else{
                        $content = new AnnouncementInfo();
                    }

                    //Dil değiştiyse slug değiştir.
                    if(session('langChanged')){
                        if($subSlug){
                            $uid = Announcement::where('slug',$subSlug)->first()->uid;
                            $newSlug = Announcement::where('uid',$uid)->where('lang_code',$locale)->first()->slug;
                            return redirect()->route('doubleSlug', [$content->slug, $newSlug]);
                        }else{
                            return redirect()->route('slug', [$content->slug]);
                        }
                    }

                    //Slug diline göre lang_code session tekrar atama
                    $targetLangCode = Menu::where('content_slug',$slug)->first()->lang_code;
                    if($targetLangCode != session('locale')){
                        session()->put('locale',$targetLangCode);
                        $item = AnnouncementInfo::where('lang_code',$targetLangCode)->first();
                        return redirect()->route('slug',[$item->slug]);
                    }

                    //Eğer içerik pasifse anasayfa yönlendir.
                    if(session('is_desktop')){
                        if(!$content->is_desktop_active){
                            return redirect()->route('index');
                        }
                    }else{
                        if(!$content->is_mobile_active){
                            return redirect()->route('index');
                        }
                    }
                    if($subSlug){
                        //Resimleri alma.
                        //subSlug $content->slug eşitse ana duyuru sayfası resimleri, değilse gelen duyuru bilgileri ile duyuru detay sayfası.
                        if($subSlug == $content->slug){
                            $images = Gallery::where('menu_code','announcement')->where('lang_code',$locale)->where('is_active',1)->get();

                            //Breadcrumb
                            $breadcrumbContents = [];
                            array_push($breadcrumbContents, $content);
                            array_push($breadcrumbContents, $content);

                            return view('announcement-detail',[
                                'appSetting' => $appSetting,
                                'header' => $header,
                                'footer' => $footer,
                                'menus' => $menus,
                                'productCategories' => $productCategories,
                                'contact' => $contact,
                                'productsInfo' => $productsInfo,
                                'langs' => Language::orderBy('order','asc')->get(),
                                'content' => $content,
                                'images' => $images,
                                'constant' => $constant,
                                'breadcrumbContents' => $breadcrumbContents,
                                'homePageMenu' => Menu::where('menu_code','index')->where('lang_code',$locale)->first()
                            ]);
                        }else{

                            //Breadcrumb
                            $breadcrumbContents = [];
                            array_push($breadcrumbContents, $content);
                            array_push($breadcrumbContents, Announcement::where('slug',$subSlug)->first());

                            $content = Announcement::where('slug',$subSlug)->first();

                            //Eğer içerik pasifse anasayfa yönlendir.
                            if(!$content->is_active){
                                return redirect()->route('index');
                            }

                            //Slug diline göre lang_code session tekrar atama
                            $targetLangCode = Announcement::where('slug',$subSlug)->first()->lang_code;
                            if($targetLangCode != session('locale')){
                                session()->put('locale',$targetLangCode);
                                $mainItem = AnnouncementInfo::where('lang_code',$targetLangCode)->first();
                                $item = Announcement::where('slug',$subSlug)->where('lang_code',$targetLangCode)->first();
                                return redirect()->route('doubleSlug',[$mainItem->slug, $item->slug]);
                            }

                            $images = AnnouncementImage::where('announcement_id',$content->id)->where('is_active',1)->orderBy('order','asc')->get();
                            return view('announcement-detail',[
                                'appSetting' => $appSetting,
                                'header' => $header,
                                'footer' => $footer,
                                'menus' => $menus,
                                'productCategories' => $productCategories,
                                'contact' => $contact,
                                'productsInfo' => $productsInfo,
                                'langs' => Language::orderBy('order','asc')->get(),
                                'content' => $content,
                                'images' => $images,
                                'constant' => $constant,
                                'breadcrumbContents' => $breadcrumbContents,
                                'homePageMenu' => Menu::where('menu_code','index')->where('lang_code',$locale)->first()
                            ]);
                        }

                        //subSlug yoksa ana duyuru sayfasına
                    }else{

                        //Pagination, order'a göre çalışıyor.
                        $page = 1;
                        $pageLimit = $announcementInfo->page_limit;
                        $pageCount = ceil(count(Announcement::where('lang_code',$locale)->where('is_active',1)->get()) / $pageLimit);
                        if($pageCount<1){
                            $pageCount = 1;
                        }
                        $pageContents = [];

                        $i = 0;
                        $pageCounter = 0;
                        $tmpArr = [];
                        foreach(Announcement::where('lang_code',$locale)->where('is_active',1)->get() as $item){
                            if($i == $pageLimit){
                                array_push($pageContents,$tmpArr);
                                $tmpArr = [];
                                $i = 0;
                                $pageCounter++;
                            }
                            array_push($tmpArr,$item);
                            $i++;
                        }
                        if($i>0){   //Kalan itemler
                            array_push($pageContents,$tmpArr);
                        }

                        //Pagination Son
                        if($request->page){
                            $page = $request->page;
                        }
                        if($page>$pageCount){
                            return view('404');
                        }

                        //Eğer içerik pasifse anasayfa yönlendir.
                        if(session('is_desktop')){
                            if(!$content->is_desktop_active){
                                return redirect()->route('index');
                            }
                        }else{
                            if(!$content->is_mobile_active){
                                return redirect()->route('index');
                            }
                        }
                        return view('announcement',[
                            'appSetting' => $appSetting,
                            'header' => $header,
                            'footer' => $footer,
                            'menus' => $menus,
                            'productCategories' => $productCategories,
                            'contact' => $contact,
                            'langs' => Language::orderBy('order','asc')->get(),
                            'productsInfo' => $productsInfo,
                            'content' => $content,
                            'page' => $page,
                            'pageCount' => $pageCount,
                            'pageLimit' => $pageLimit,
                            'pageContents' => $pageContents,
                            'constant' => $constant,
                            'homePageMenu' => Menu::where('menu_code','index')->where('lang_code',$locale)->first()
                        ]);
                    }
                    break;
                    */
                case 'references':
                    /*
                    if(null != ReferenceInfo::where('lang_code',$locale)->first()){
                        $content = ReferenceInfo::where('lang_code',$locale)->first();
                    }else{
                        $content = new ReferenceInfo();
                    }

                    //Dil değiştiyse slug değiştir.
                    if(session('langChanged')){
                        return redirect()->route('slug', [$content->slug]);
                    }

                    //Eğer içerik pasifse anasayfa yönlendir.
                    if(session('is_desktop')){
                        if(!$content->is_desktop_active){
                            return redirect()->route('index');
                        }
                    }else{
                        if(!$content->is_mobile_active){
                            return redirect()->route('index');
                        }
                    }

                    //Slug diline göre lang_code session tekrar atama
                    $targetLangCode = Menu::where('content_slug',$slug)->first()->lang_code;
                    if($targetLangCode != session('locale')){
                        session()->put('locale',$targetLangCode);
                        $item = ReferenceInfo::where('lang_code',$targetLangCode)->first();
                        return redirect()->route('slug',[$item->slug]);
                    }
                    */

                    $references = Reference::where('is_active',1)->where('lang_code',$locale)->orderBy('order','asc')->get();
                    return view('references',[
                        'appSetting' => $appSetting,
                        'header' => $header,
                        'footer' => $footer,
                        'menus' => $menus,
                        'productCategories' => $productCategories,
                        'productsInfo' => $productsInfo,
                        'contact' => $contact,
                        'langs' => Language::orderBy('order','asc')->get(),
                        'references' => $references,
                        'constant' => $constant,
                        'homePageMenu' => Menu::where('menu_code','index')->where('lang_code',$locale)->first(),
                        /*
                        'content' => $content,
                        */
                    ]);
                    break;
                case 'prices':

                    if(null != PricingInfo::where('lang_code',$locale)->first()){
                        $content = PricingInfo::where('lang_code',$locale)->first();
                    }else{
                        $content = new PricingInfo();
                    }

                    //Dil değiştiyse slug değiştir.
                    if(session('langChanged')){
                        return redirect()->route('slug', [$content->slug]);
                    }

                    //Eğer içerik pasifse anasayfa yönlendir.
                    if(session('is_desktop')){
                        if(!$content->is_desktop_active){
                            return redirect()->route('index');
                        }
                    }else{
                        if(!$content->is_mobile_active){
                            return redirect()->route('index');
                        }
                    }

                    if(null != Reference::where('lang_code',$locale)->orderBy('order','asc')->get()){
                        $references = Reference::where('lang_code',$locale)->orderBy('order','asc')->get();
                    }else{
                        $references = new Reference();
                    }

                    /**
                     * Price Detail
                     */
                    if($subSlug){
                        $price = Pricing::where('slug',$subSlug)->first();
                        return view('price-detail',[
                            'appSetting' => $appSetting,
                            'header' => $header,
                            'footer' => $footer,
                            'menus' => $menus,
                            'references' => $references,
                            'productCategories' => $productCategories,
                            'productsInfo' => $productsInfo,
                            'contact' => $contact,
                            'langs' => Language::orderBy('order','asc')->get(),
                            'content' => $content,
                            'price' => $price,
                            'constant' => $constant,
                            'homePageMenu' => Menu::where('menu_code','index')->where('lang_code',$locale)->first()
                        ]);
                    }

                    //Slug diline göre lang_code session tekrar atama
                    $targetLangCode = Menu::where('content_slug',$slug)->first()->lang_code;
                    if($targetLangCode != session('locale')){
                        session()->put('locale',$targetLangCode);
                        $item = PricingInfo::where('lang_code',$targetLangCode)->first();
                        return redirect()->route('slug',[$item->slug]);
                    }


                    $prices = Pricing::where('is_active',1)->where('lang_code',$locale)->orderBy('order','asc')->get();
                    return view('prices',[
                        'appSetting' => $appSetting,
                        'header' => $header,
                        'footer' => $footer,
                        'menus' => $menus,
                        'references' => $references,
                        'productCategories' => $productCategories,
                        'productsInfo' => $productsInfo,
                        'contact' => $contact,
                        'langs' => Language::orderBy('order','asc')->get(),
                        'content' => $content,
                        'prices' => $prices,
                        'constant' => $constant,
                        'homePageMenu' => Menu::where('menu_code','index')->where('lang_code',$locale)->first(),
                    ]);
                    break;
                case 'gallery_info':
                    if(null != GalleryInfo::where('lang_code',$locale)->first()){
                        $content = GalleryInfo::where('lang_code',$locale)->first();
                    }else{
                        $content = new GalleryInfo();
                    }
                    /**
                     * Dil değiştiyse slug değiştir.
                     */
                    if(session('langChanged')){
                        return redirect()->route('slug', [$content->slug]);
                    }
                    /**
                     * Eğer içerik pasifse anasayfa yönlendir.
                     */
                    if(session('is_desktop')){
                        if(!$content->is_desktop_active){
                            return redirect()->route('index');
                        }
                    }else{
                        if(!$content->is_mobile_active){
                            return redirect()->route('index');
                        }
                    }
                    /**
                     * Slug diline göre lang_code session tekrar atama
                     */
                    $targetLangCode = Menu::where('content_slug',$slug)->first()->lang_code;
                    if($targetLangCode != session('locale')){
                        session()->put('locale',$targetLangCode);
                        $item = GalleryInfo::where('lang_code',$targetLangCode)->first();
                        return redirect()->route('slug',[$item->slug]);
                    }

                    $images = Gallery::where('menu_code','gallery_info')->where('lang_code',$locale)->orderBy('order','asc')->get();
                    return view('gallery',[
                        'appSetting' => $appSetting,
                        'header' => $header,
                        'footer' => $footer,
                        'menus' => $menus,
                        'productCategories' => $productCategories,
                        'productsInfo' => $productsInfo,
                        'contact' => $contact,
                        'langs' => Language::orderBy('order','asc')->get(),
                        'content' => $content,
                        'images' => $images,
                        'constant' => $constant,
                        'homePageMenu' => Menu::where('menu_code','index')->where('lang_code',$locale)->first()
                    ]);
                    break;
                case 'products_info':
                    /**
                     * Ürün detay sayfasına gidilecek.
                     */
                    if($request->detail) {
                        /**
                         * Ürün ID'sinin slug'tan alınması
                         */
                        if($lastSlug){
                            $content_slug = explode('-',$lastSlug);
                            $product_id = $content_slug[count($content_slug)-1];
                        }else if($subSubSubSlug){
                            $content_slug = explode('-',$subSubSubSlug);
                            $product_id = $content_slug[count($content_slug)-1];
                        }else if($subSubSlug){
                            $content_slug = explode('-',$subSubSlug);
                            $product_id = $content_slug[count($content_slug)-1];
                        }else if($subSlug){
                            $content_slug = explode('-',$subSlug);
                            $product_id = $content_slug[count($content_slug)-1];
                        }

                        /**
                         * Kategori id'si slug'tan alınıyor.
                         * Eğer Kategoriden geldiyse bir önceki slug kategori slug yoksa ir önceki slug ürünler sayfası slug.(kategori slug = Null)
                         */
                        $category_id = null;
                        if($lastSlug){
                            $content_slug = explode('-',$subSubSubSlug);
                            $category_id = $content_slug[count($content_slug)-1];
                        }else if($subSubSubSlug){
                            $content_slug = explode('-',$subSubSlug);
                            $category_id = $content_slug[count($content_slug)-1];
                        }else if($subSubSlug){
                            $content_slug = explode('-',$subSlug);
                            $category_id = $content_slug[count($content_slug)-1];
                        }
                        /**
                         * Seçili kategori ve parentları, multi kategori için. Ürünlerin url'leri için.
                         */
                        $parentCategories = [];
                        $selectedCategory = null;
                        if($category_id){
                            $selectedCategory = ProductCategory::findOrFail($category_id);
                            array_push($parentCategories,$selectedCategory);
                            if($selectedCategory->parent_category_uid != '0'){
                                foreach($productCategories as $item){
                                    if($item->uid == $selectedCategory->parent_category_uid){
                                        $selectedCategory = $item;
                                        array_push($parentCategories,$selectedCategory);
                                    }
                                }
                            }
                            if($selectedCategory->parent_category_uid != '0'){
                                foreach($productCategories as $item){
                                    if($item->uid == $selectedCategory->parent_category_uid){
                                        $selectedCategory = $item;
                                        array_push($parentCategories,$selectedCategory);
                                    }
                                }
                            }
                            if($selectedCategory->parent_category_uid != '0'){
                                foreach($productCategories as $item){
                                    if($item->uid == $selectedCategory->parent_category_uid){
                                        $selectedCategory = $item;
                                        array_push($parentCategories,$selectedCategory);
                                    }
                                }
                            }
                            if($selectedCategory->parent_category_uid != '0'){
                                foreach($productCategories as $item){
                                    if($item->uid == $selectedCategory->parent_category_uid){
                                        $selectedCategory = $item;
                                        array_push($parentCategories,$selectedCategory);
                                    }
                                }
                            }
                        }

                        if(null != ProductsInfo::where('lang_code',$locale)->first()){
                            $content = ProductsInfo::where('lang_code',$locale)->first();
                        }else{
                            return redirect()->route('index');
                        }
                        $images = ProductImage::where('product_id',$product_id)->where('is_active',1)->orderBy('order','asc')->get();

                        /**
                         * Dil değiştiyse slug değiştir.
                         */
                        if(session('langChanged')){
                            if($lastSlug && $subSubSubSlug && $subSubSlug && $subSlug){
                                //İlk kategori slug
                                $firstUid = ProductCategory::where('slug',$subSlug)->first()->uid;
                                $firstSlug = ProductCategory::where('uid',$firstUid)->where('lang_code',$locale)->first()->slug;
                                //İkinci kategori slug
                                $secondUid = ProductCategory::where('slug',$subSubSlug)->first()->uid;
                                $secondSlug = ProductCategory::where('uid',$secondUid)->where('lang_code',$locale)->first()->slug;
                                //Üçüncü kategori slug
                                $thirdUid = ProductCategory::where('slug',$subSubSubSlug)->first()->uid;
                                $thirdSlug = ProductCategory::where('uid',$thirdUid)->where('lang_code',$locale)->first()->slug;
                                //Son ürün slug
                                $lastUid = Product::where('slug',$lastSlug)->first()->uid;
                                $lastSlug = Product::where('uid',$lastUid)->where('lang_code',$locale)->first()->slug;
                                $newId = Product::where('uid',$lastUid)->where('lang_code',$locale)->first()->id;
                                return redirect()->route('fiveSlug', [$content->slug, $firstSlug, $secondSlug, $thirdSlug, $lastSlug, 'detail' => $newId]);
                            }else if($subSubSubSlug && $subSubSlug && $subSlug){
                                //İlk kategori slug
                                $firstUid = ProductCategory::where('slug',$subSlug)->first()->uid;
                                $firstSlug = ProductCategory::where('uid',$firstUid)->where('lang_code',$locale)->first()->slug;
                                //İkinci kategori slug
                                $secondUid = ProductCategory::where('slug',$subSubSlug)->first()->uid;
                                $secondSlug = ProductCategory::where('uid',$secondUid)->where('lang_code',$locale)->first()->slug;
                                //Üçüncü kategori slug
                                $thirdUid = Product::where('slug',$subSubSubSlug)->first()->uid;
                                $thirdSlug = Product::where('uid',$thirdUid)->where('lang_code',$locale)->first()->slug;
                                $newId = Product::where('uid',$thirdUid)->where('lang_code',$locale)->first()->id;
                                return redirect()->route('fourSlug', [$content->slug, $firstSlug, $secondSlug, $thirdSlug, 'detail' => $newId]);
                            }else if($subSubSlug && $subSlug){
                                //İlk kategori slug
                                $firstUid = ProductCategory::where('slug',$subSlug)->first()->uid;
                                $firstSlug = ProductCategory::where('uid',$firstUid)->where('lang_code',$locale)->first()->slug;
                                //İkinci kategori slug
                                $secondUid = Product::where('slug',$subSubSlug)->first()->uid;
                                $secondSlug = Product::where('uid',$secondUid)->where('lang_code',$locale)->first()->slug;
                                $newId = Product::where('uid',$secondUid)->where('lang_code',$locale)->first()->id;
                                return redirect()->route('tripleSlug', [$content->slug, $firstSlug, $secondSlug, 'detail' => $newId]);
                            }else if($subSlug){
                                $uid = Product::where('slug',$subSlug)->first()->uid;
                                $newSlug = Product::where('uid',$uid)->where('lang_code',$locale)->first()->slug;
                                $newId = Product::where('uid',$uid)->where('lang_code',$locale)->first()->id;
                                return redirect()->route('doubleSlug', [$content->slug, $newSlug, 'detail' => $newId]);
                            }else{
                                return redirect()->route('slug', [$content->slug]);
                            }
                        }
                         /**
                         * Slug diline göre lang_code session tekrar atama
                         */
                        if($lastSlug && $subSubSubSlug && $subSubSlug && $subSlug){
                            $targetProduct = Product::where('slug',$lastSlug)->first();
                            if($targetProduct->lang_code != session('locale')){
                                $content = ProductsInfo::where('lang_code',$targetProduct->lang_code)->first();
                                session()->put('locale',$targetProduct->lang_code);
                                return redirect()->route('fiveSlug', [$content->slug, $subSlug, $subSubSlug, $subSubSubSlug, $lastSlug, 'detail' => $targetProduct->id]);
                            }
                        }else if($subSubSubSlug && $subSubSlug && $subSlug){
                            $targetProduct = Product::where('slug',$subSubSubSlug)->first();
                            if($targetProduct->lang_code != session('locale')){
                                $content = ProductsInfo::where('lang_code',$targetProduct->lang_code)->first();
                                session()->put('locale',$targetProduct->lang_code);
                                return redirect()->route('fourSlug', [$content->slug, $subSlug, $subSubSlug, $subSubSubSlug, 'detail' => $targetProduct->id]);
                            }
                        }else if($subSubSlug && $subSlug){
                            $targetProduct = Product::where('slug',$subSubSlug)->first();
                            if($targetProduct->lang_code != session('locale')){
                                $content = ProductsInfo::where('lang_code',$targetProduct->lang_code)->first();
                                session()->put('locale',$targetProduct->lang_code);
                                return redirect()->route('tripleSlug', [$content->slug, $subSlug, $subSubSlug, 'detail' => $targetProduct->id]);
                            }
                        }else if($subSlug){
                            $targetProduct = Product::where('slug',$subSlug)->first();
                            if($targetProduct->lang_code != session('locale')){
                                $content = ProductsInfo::where('lang_code',$targetProduct->lang_code)->first();
                                session()->put('locale',$targetProduct->lang_code);
                                return redirect()->route('doubleSlug', [$content->slug, $subSlug, 'detail' => $targetProduct->id]);
                            }
                        }else{
                            $targetLangCode = Menu::where('content_slug',$slug)->first()->lang_code;
                            if($targetLangCode != session('locale')){
                                session()->put('locale',$targetLangCode);
                                $item = ProductsInfo::where('lang_code',$targetLangCode)->first();
                                return redirect()->route('slug',[$item->slug]);
                            }
                        }
                        /**
                         * Eğer içerik pasifse anasayfa yönlendir.
                         */
                        $content = Product::findOrFail($product_id);
                        if(!$content->is_active){
                            return redirect()->route('index');
                        }

                        return view('products-detail',[
                            'appSetting' => $appSetting,
                            'header' => $header,
                            'footer' => $footer,
                            'menus' => $menus,
                            'productCategories' => $productCategories,
                            'productsInfo' => $productsInfo,
                            'contact' => $contact,
                            'langs' => Language::orderBy('order','asc')->get(),
                            'content' => $content,
                            'images' => $images,
                            'constant' => $constant,
                            'breadcrumbContents' => array_reverse($parentCategories),
                            'homePageMenu' => Menu::where('menu_code','index')->where('lang_code',$locale)->first()
                        ]);
                    }

                    if(null != ProductsInfo::where('lang_code',$locale)->first()){
                        $content = ProductsInfo::where('lang_code',$locale)->first();
                        if(session('is_desktop')){
                            if(!$content->is_desktop_active){
                                return redirect()->route('index');
                            }
                        }else{
                            if(!$content->is_mobile_active){
                                return redirect()->route('index');
                            }
                        }
                    }else{
                        return redirect()->route('index');
                    }
                    /**
                     * Dil değiştiyse slug değiştir.
                     */
                    if(session('langChanged')){
                        if($subSubSubSlug && $subSubSlug && $subSlug){
                            //İlk kategori slug
                            $firstUid = ProductCategory::where('slug',$subSlug)->first()->uid;
                            $firstSlug = ProductCategory::where('uid',$firstUid)->where('lang_code',$locale)->first()->slug;
                            //İkinci kategori slug
                            $secondUid = ProductCategory::where('slug',$subSubSlug)->first()->uid;
                            $secondSlug = ProductCategory::where('uid',$secondUid)->where('lang_code',$locale)->first()->slug;
                            //üçüncü kategori slug
                            $thirdUid = ProductCategory::where('slug',$subSubSubSlug)->first()->uid;
                            $thirdSlug = ProductCategory::where('uid',$thirdUid)->where('lang_code',$locale)->first()->slug;
                            return redirect()->route('fourSlug', [$content->slug, $firstSlug, $secondSlug, $thirdSlug]);
                        }else if($subSubSlug && $subSlug){
                            //İlk kategori slug
                            $firstUid = ProductCategory::where('slug',$subSlug)->first()->uid;
                            $firstSlug = ProductCategory::where('uid',$firstUid)->where('lang_code',$locale)->first()->slug;
                            //İkinci kategori slug
                            $secondUid = ProductCategory::where('slug',$subSubSlug)->first()->uid;
                            $secondSlug = ProductCategory::where('uid',$secondUid)->where('lang_code',$locale)->first()->slug;
                            return redirect()->route('tripleSlug', [$content->slug, $firstSlug, $secondSlug]);
                        }else if($subSlug){
                            $uid = ProductCategory::where('slug',$subSlug)->first()->uid;
                            $newSlug = ProductCategory::where('uid',$uid)->where('lang_code',$locale)->first()->slug;
                            return redirect()->route('doubleSlug', [$content->slug, $newSlug]);
                        }else{
                            return redirect()->route('slug', [$content->slug]);
                        }
                    }

                    /**
                     * Slug diline göre lang_code session tekrar atama
                     */
                    if(!$request->detail){
                        if($subSubSubSlug && $subSubSlug && $subSlug){
                            $targetLangCode = ProductCategory::where('slug',$subSubSubSlug)->first()->lang_code;
                            if($targetLangCode != session('locale')){
                                $content = ProductsInfo::where('lang_code',$targetLangCode)->first();
                                session()->put('locale',$targetLangCode);
                                return redirect()->route('fourSlug', [$content->slug, $subSlug, $subSubSlug, $subSubSubSlug]);
                            }
                        }else if($subSubSlug && $subSlug){
                            $targetLangCode = ProductCategory::where('slug',$subSubSlug)->first()->lang_code;
                            if($targetLangCode != session('locale')){
                                $content = ProductsInfo::where('lang_code',$targetLangCode)->first();
                                session()->put('locale',$targetLangCode);
                                return redirect()->route('tripleSlug', [$content->slug, $subSlug, $subSubSlug]);
                            }
                        }else if($subSlug){
                            $targetLangCode = ProductCategory::where('slug',$subSlug)->first()->lang_code;
                            if($targetLangCode != session('locale')){
                                $content = ProductsInfo::where('lang_code',$targetLangCode)->first();
                                session()->put('locale',$targetLangCode);
                                return redirect()->route('doubleSlug', [$content->slug, $subSlug]);
                            }
                        }else{
                            $targetLangCode = Menu::where('content_slug',$slug)->first()->lang_code;
                            if($targetLangCode != session('locale')){
                                session()->put('locale',$targetLangCode);
                                $item = ProductsInfo::where('lang_code',$targetLangCode)->first();
                                return redirect()->route('slug',[$item->slug]);
                            }
                        }

                    }

                    /**
                     * Kategori id'si slug'tan alınıyor. Null ise ürünler demek.
                     */
                    $category_id = null;
                    if($subSubSubSlug && $subSubSlug && $subSlug){
                        $content_slug = explode('-',$subSubSubSlug);
                        $category_id = $content_slug[count($content_slug)-1];
                    }else if($subSubSlug && $subSlug){
                        $content_slug = explode('-',$subSubSlug);
                        $category_id = $content_slug[count($content_slug)-1];
                    }else if($subSlug){
                        $content_slug = explode('-',$subSlug);
                        $category_id = $content_slug[count($content_slug)-1];
                    }

                    /**
                     * Seçili kategori ve parentları, multi kategori için. Ürünlerin url'leri için.
                     */
                    $parentCategories = [];
                    $selectedCategory = null;
                    if($category_id){
                        $selectedCategory = ProductCategory::findOrFail($category_id);
                        array_push($parentCategories,$selectedCategory);
                        if($selectedCategory->parent_category_uid != '0'){
                            foreach($productCategories as $item){
                                if($item->uid == $selectedCategory->parent_category_uid){
                                    $selectedCategory = $item;
                                    array_push($parentCategories,$selectedCategory);
                                }
                            }
                        }
                        if($selectedCategory->parent_category_uid != '0'){
                            foreach($productCategories as $item){
                                if($item->uid == $selectedCategory->parent_category_uid){
                                    $selectedCategory = $item;
                                    array_push($parentCategories,$selectedCategory);
                                }
                            }
                        }
                        if($selectedCategory->parent_category_uid != '0'){
                            foreach($productCategories as $item){
                                if($item->uid == $selectedCategory->parent_category_uid){
                                    $selectedCategory = $item;
                                    array_push($parentCategories,$selectedCategory);
                                }
                            }
                        }
                    }
                    $productCount = count(Product::where('lang_code',$locale)->where('is_active',1)->get());
                    $pivots = ProductCategoryPivot::orderBy('product_order','asc')->get();
                    $products = Product::where('lang_code',$locale)->where('is_active',1)->orderBy('order','asc')->get();

                    /**
                     * Kategoriye ait olan ürünler dizisi.
                     */
                    $categoriesProducts = [];
                    if(!$category_id){
                        /**
                         * Kategori seçili değilse ürün sıralaması.
                         */
                        $categoriesProducts = Product::where('lang_code',$locale)->where('is_active',1)->orderBy('order','asc')->get();
                    }else{
                        /**
                         * Ürünlerin kategori içeirisindeki sıralaması
                         */
                        foreach($pivots as $pivot){
                            if($pivot->category_id == $category_id){
                                array_push($categoriesProducts,Product::findOrFail($pivot->product_id));
                            }
                        }
                    }
                    /**
                    * Pagination, order'a göre çalışıyor.
                    */
                   $page = 1;
                   $pageLimit = $productsInfo->page_limit;;
                   $pageCount = ceil(count($categoriesProducts) / $pageLimit);
                   if($pageCount<1){
                       $pageCount = 1;
                   }
                   $products = [];

                   $i = 0;
                   $pageCounter = 0;
                   $tmpArr = [];
                   foreach($categoriesProducts as $item){
                       if($i == $pageLimit){
                           array_push($products,$tmpArr);
                           $tmpArr = [];
                           $i = 0;
                           $pageCounter++;
                       }
                       array_push($tmpArr,$item);
                       $i++;
                   }
                   if($i>0){   //Kalan itemler
                       array_push($products,$tmpArr);
                   }
                   if($request->page){
                       $page = $request->page;
                   }
                   if($page>$pageCount){
                       return view('404');
                   }
                   /**
                    * Pagination Son
                    */
                    if($category_id){
                        $selectedCategory = ProductCategory::findOrFail($category_id);
                        /**
                         * Eğer içerik pasifse anasayfa yönlendir.
                         */
                        if(!$selectedCategory->is_active){
                            return redirect()->route('index');
                        }
                        /**
                         * O kategorinin alt kategorileri de gidilemez.
                         */
                        foreach(ProductCategory::where('uid',$selectedCategory->parent_category_uid)->where('lang_code',$locale)->get() as $item){
                            if(!$item->is_active){
                                return redirect()->route('index');
                            }
                        }
                    }else{
                        $selectedCategory = null;
                    }
                    return view('products',[
                        'appSetting' => $appSetting,
                        'header' => $header,
                        'footer' => $footer,
                        'menus' => $menus,
                        'productCategories' => $productCategories,
                        'contact' => $contact,
                        'langs' => Language::orderBy('order','asc')->get(),
                        'productsInfo' => $productsInfo,
                        'content' => $content,
                        'selectedCategory' => $selectedCategory,
                        'page' => $page,
                        'pageCount' => $pageCount,
                        'productCount' => $productCount,
                        'pivots' => $pivots,
                        'products' => Product::where('lang_code',$locale)->where('is_active',1)->orderBy('order','asc')->get(),
                        'parentCategories' => $parentCategories,
                        'constant' => $constant,
                        'breadcrumbContents' => array_reverse($parentCategories),
                        'homePageMenu' => Menu::where('menu_code','index')->where('lang_code',$locale)->first()
                    ]);
                    break;
                /*
                case 'page':
                    $content_id = last(explode('-',$slug));
                    $content = Page::where('id', $content_id)->first();

                    //Dil değiştiyse anasayfa yönlendir
                    if(session('langChanged')){
                        return redirect()->route('index');
                    }

                    //Eğer içerik pasifse anasayfa yönlendir.
                    if(session('is_desktop')){
                        if(!$content->is_desktop_active){
                            return redirect()->route('index');
                        }
                    }else{
                        if(!$content->is_mobile_active){
                            return redirect()->route('index');
                        }
                    }

                    //Slug diline göre lang_code session tekrar atama
                    $targetLangCode = $content->lang_code;
                    if($targetLangCode != session('locale')){
                        session()->put('locale',$targetLangCode);
                        $item = Page::where('slug',$slug)->first();
                        return redirect()->route('slug',[$item->slug]);
                    }

                    $images = PageImage::where('page_id',$content->id)->where('is_active',1)->orderBy('order','asc')->get();
                    return view('page-detail',[
                        'appSetting' => $appSetting,
                        'header' => $header,
                        'footer' => $footer,
                        'menus' => $menus,
                        'productCategories' => $productCategories,
                        'contact' => $contact,
                        'langs' => Language::orderBy('order','asc')->get(),
                        'productsInfo' => $productsInfo,
                        'content' => $content,
                        'images' => $images,
                        'constant' => $constant,
                        'homePageMenu' => Menu::where('menu_code','index')->where('lang_code',$locale)->first()
                    ]);
                    break;
                */
            }
        }else{
            /**
             * Lang code olarak bir slug geliyorsa anasayfada lang code ile göster yoksa 404
            */
            $lang_codes = [];
            foreach(Language::all() as $l){
                array_push($lang_codes, $l->lang_code);
            }
            if(in_array($slug, $lang_codes)){
                if($slug != $locale){
                    session()->forget('locale');
                    session()->put('locale', $slug);
                    session()->flash('langChanged', true);
                    return redirect()->route('slug',$slug);
                }else{
                    return $this->homePage();
                }
            }
            return view('404');
        }
    }
}




