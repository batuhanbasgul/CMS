<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageManager;
use App\Models\AboutUsCards;
use App\Models\Language;
use App\Services\FileManagerService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class AboutUsCardsController extends Controller
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
         * User Preferences Sessions for language,
         */
        if(!session('lang_code')){
            session()->forget('lang_code');
            session()->put('lang_code', App::getLocale());
        }else{
            App::setLocale(session('lang_code'));
        }

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Sidebar selected item
         */
        session()->forget('selectedSideMenu');
        session()->put('selectedSideMenu',last(explode('/',URL::current())));

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Works with lang_code parameter;
         * if null default is "tr"
         * if not null and no content on that language reroutes to "add" page.
         * if not null and there is content for that language, reroutes to edit page with lang_code, cards, langs parameters.
         */
        if ($request->lang_code) {
            return view('admin.about-us-cards-settings', ['langs' => Language::orderBy('order','asc')->get(), 'cards' => AboutUsCards::where('lang_code', $request->lang_code)->get(), 'lang_code' => $request->lang_code]);
        } else {
            return view('admin.about-us-cards-settings', [
                'langs' => Language::orderBy('order','asc')->get(),
                'cards' => AboutUsCards::where('lang_code', session('lang_code'))->get(),
                'lang_code' => session('lang_code'),
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
         * Routes to creating page with lang code. And uses that lang code to create.
         */
        return view('admin.about-us-cards-settings-add', ['lang_code' => $request->lang_code, 'langs' => Language::all()]);
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

        $card = new AboutUsCards();
        $card->title = $request->title;
        $card->subtitle = $request->subtitle;
        $card->description = $request->description;
        $card->icon = $request->icon;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * On same content but different languages has same "menu_code" for matching.
         * UID for just one menu and one content.
         */
        $card->uid = $uniqueid;
        $card->lang_code = $request->lang_code;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Add to last order.
         */
        $a_order = 0;
        foreach (AboutUsCards::where('lang_code', $request->lang_code)->get() as $a) {
            if ($a->order > $a_order) {
                $a_order = $a->order;
            }
        }
        $card->order = $a_order + 1;
        if($card->save()){
            $request->session()->flash('success', 'Başarıyla oluşturuldu');
            return redirect()->route('admin.about-us-cards-settings.index', ['lang_code' => $request->lang_code]);
        }else{
            $request->session()->flash('error', 'Kaydetme Başarısız');
            return redirect()->route('admin.about-us-cards-settings.index', ['lang_code' => $request->lang_code]);
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
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Selected card - $card
         * All cards on that language - $cards
         */
        $card = AboutUsCards::findOrFail($id);
        $cards = AboutUsCards::where('lang_code', $card->lang_code)->orderBy('order', 'asc')->get();
        return view('admin.about-us-cards-settings-update', [
            'card' => $card,
            'cards' => $cards,
            'lang_code' => $card->lang_code,
            'langs' => Language::all(),
            'large_size' => ImageManager::where('type','hakkimizda_kart_resim_geniş')->first(),
            'mobile_size' => ImageManager::where('type','hakkimizda_kart_resim_mobil')->first(),
        ]);
    }

    /** ►►►►► DEVELOPER ◄◄◄◄◄
     * Order management page.
     */
    /**
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
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Check if there is cards on that language.
         */
        $cards = AboutUsCards::where('lang_code', $request->lang_code)->orderBy('order','asc')->get();
        if(count($cards) == 0){
            $request->session()->flash('no_card','Hakkımızda Kart Kaydı Bulunamadı');
            return back();
        }
        return view('admin.about-us-cards-settings-update-order', [
            'cards' => $cards,
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
        $card = AboutUsCards::findOrFail($id);

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
                $imageManager = ImageManager::where('type', 'hakkimizda_kart_resim_geniş')->first();
                $imageManagerThumbnail = ImageManager::where('type','hakkimizda_kart_thumbnail_geniş')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($card->title.'-l',$request->cropped_data,'images',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult = $this->fileManagerService->uploadImage($card->title.'-l',$request->cropped_data,'images/thumbnail',$imageManagerThumbnail);
                if($thumbnailResult == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old files if there is.
                 */
                if (file_exists($card->image_large_screen)) {
                    unlink($card->image_large_screen);
                }
                if (file_exists($card->thumbnail_large_screen)) {
                    unlink($card->thumbnail_large_screen);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $card->image_large_screen = $result;
                $card->thumbnail_large_screen = $thumbnailResult;
                if ($card->save()) {
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
                $imageManager = ImageManager::where('type', 'hakkimizda_kart_resim_mobil')->first();
                $imageManagerThumbnail = ImageManager::where('type','hakkimizda_kart_thumbnail_mobil')->first();

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($card->title.'-m',$request->cropped_data_mobile,'images',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult = $this->fileManagerService->uploadImage($card->title.'-m',$request->cropped_data_mobile,'images/thumbnail',$imageManagerThumbnail);
                if($thumbnailResult == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old files if there is.
                 */
                if (file_exists($card->image_small_screen)) {
                    unlink($card->image_small_screen);
                }
                if (file_exists($card->thumbnail_small_screen)) {
                    unlink($card->thumbnail_small_screen);
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $card->image_small_screen = $result;
                $card->thumbnail_small_screen = $thumbnailResult;
                if ($card->save()) {
                    $request->session()->flash('success', 'Ürün Güncellendi');
                } else {
                    $request->session()->flash('error', 'Ürün Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_small');                 //Which tab will be opened.
            return back();
        }else if($request->has('updateaboutuscardssettings')){

            $card->title = $request->title;
            $card->subtitle = $request->subtitle;
            $card->description = $request->description;
            $card->icon = $request->icon;

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Switch tool returns 'on' or null.
             */
            if ('on' == $request->is_active) {
                $card->is_active = 1;
            } else {
                $card->is_active = 0;
            }

            if ($card->save()) {
                $request->session()->flash('success', 'Başarıla Güncellendi');
            } else {
                $request->session()->flash('error', 'Güncelleme Başarısız');
            }
            session()->flash('tab_page','context');                 //Which tab will be opened.
            return back();
        }else if($request->has('updateaboutuscardsorder')){

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Returns locations of about us card's on order and re-assigns orders in order to that locations.
             */
            $orders = explode(',',$request->result_h_flow);
            $cards = AboutUsCards::where('lang_code',$request->lang_code)->orderBy('order','asc')->get();
            $i=0;
            foreach($orders as $order){
                $cards[$order]->order = $i+1;
                $cards[$order]->save();
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
        $card = AboutUsCards::findOrFail($id);
        $lang_code = $card->lang_code;

        foreach (AboutUsCards::where('uid', $card->uid)->get() as $item) {
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Re-order after deleting process.
             */
            foreach (AboutUsCards::where('lang_code', $item->lang_code)->where('order', '>', $item->order)->get() as $o_item) {
                $o_item->order = $o_item->order - 1;
                $o_item->save();
            }
            $item->delete();
        }
        session()->flash('success', 'Deleted');
        return redirect()->route('admin.about-us-cards-settings.index', ['lang_code' => $lang_code]);
    }
}
