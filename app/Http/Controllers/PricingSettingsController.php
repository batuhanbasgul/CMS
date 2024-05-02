<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Pricing;
use App\Models\ImageManager;
use App\Services\FileManagerService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class PricingSettingsController extends Controller
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
         * if not null and there is content for that language, reroutes to edit page with lang_code, prices, langs parameters.
         */
        if ($request->lang_code) {
            return view('admin.pricing-settings', [
                'langs' => Language::orderBy('order','asc')->get(),
                'prices' => Pricing::where('lang_code', $request->lang_code)->get(),
                'lang_code' => $request->lang_code
            ]);
        } else {
            return view('admin.pricing-settings', [
                'langs' => Language::orderBy('order','asc')->get(),
                'prices' => Pricing::where('lang_code', session('lang_code'))->get(),
                'lang_code' => session('lang_code')
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
        return view('admin.pricing-settings-add', ['lang_code' => $request->lang_code, 'langs' => Language::all()]);
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
        $price = new Pricing();
        $price->title = $request->title;
        $price->subtitle = $request->subtitle;
        $price->description = $request->description;
        $price->keywords = $request->keywords;
        $price->video_embed_code = $request->video_embed_code;
        $price->lang_code = $request->lang_code;
        $price->pricing_url = $request->pricing_url;
        $price->entry1 = $request->entry1;
        $price->entry2 = $request->entry2;
        $price->entry3 = $request->entry3;
        $price->entry4 = $request->entry4;
        $price->entry5 = $request->entry5;
        $price->entry6 = $request->entry6;
        $price->entry7 = $request->entry7;
        $price->entry8 = $request->entry8;
        $price->entry9 = $request->entry9;
        $price->entry10 = $request->entry10;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * On same content but different languages has same "menu_code" for matching.
         * UID for just one menu and one content.
         */
        if (0 < Pricing::where('lang_code', $request->lang_code)->orderBy('id', 'desc')->get()->count()) {
            $slugCount = Pricing::where('lang_code', $request->lang_code)->orderBy('id', 'desc')->get()->first()->id + 1;
        } else {
            $slugCount = 1;
        }
        $price->slug = Str::slug($request->title. '-' .$slugCount);

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Add to last order.
         */
        $order = 0;
        foreach (Pricing::where('lang_code', $request->lang_code)->get() as $a) {
            if ($a->order > $order) {
                $order = $a->order;
            }
        }
        $price->order = $order + 1;

        if ($price->save()) {
            $request->session()->flash('success', 'Başarıla oluşturuldu');
            return redirect()->route('admin.pricing-settings.edit', [$price->id,'lang_code' => $request->lang_code,'langs' => Language::all()])->withInput();
        } else {
            $request->session()->flash('error', 'Kaydetme Başarısız');
            return redirect()->route('admin.pricing-settings.add', ['lang_code' => $request->lang_code,'langs' => Language::all()])->withInput();
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

        $prices = Pricing::where('lang_code', $request->lang_code)->orderBy('order','asc')->get();
        if(count($prices) == 0){
            $request->session()->flash('no_price','Ücret Kaydı Bulunamadı');
            return back();
        }
        return view('admin.pricing-settings-update-order', [
            'prices' => $prices,
            'langs' => Language::all()
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

        $price = Pricing::findOrFail($id);
        $prices = Pricing::where('lang_code', $price->lang_code)->orderBy('order', 'asc')->get();
        return view('admin.pricing-settings-update', [
            'price' => $price,
            'prices' => $prices,
            'page' => $request->page,
            'langs' => Language::all(),
            'large_size' => ImageManager::where('type','ucret_resim_geniş')->first(),
            'mobile_size' => ImageManager::where('type','ucret_resim_mobil')->first()
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
        $price = Pricing::findOrFail($id);

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
                $imageManager = ImageManager::where('type', 'ucret_resim_geniş')->first();
                $imageManagerThumbnail = ImageManager::where('type','ucret_thumbnail_geniş')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($price->title.'-l', $request->cropped_data, 'images', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult = $this->fileManagerService->uploadImage($price->title.'-l', $request->cropped_data, 'images/thumbnail', $imageManagerThumbnail);
                if($thumbnailResult == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old files if there is.
                 */
                if (file_exists($price->image_large_screen)) {
                    unlink($price->image_large_screen);
                }
                if (file_exists($price->thumbnail_large_screen)) {
                    unlink($price->thumbnail_large_screen);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $price->image_large_screen = $result;
                $price->thumbnail_large_screen = $thumbnailResult;
                if ($price->save()) {
                    $request->session()->flash('success', 'Ucret Güncellendi');
                } else {
                    $request->session()->flash('error', 'Ucret Güncellenemedi');
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
                $imageManager = ImageManager::where('type', 'ucret_resim_mobil')->first();
                $imageManagerThumbnail = ImageManager::where('type','ucret_thumbnail_mobil')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($price->title.'-m', $request->cropped_data_mobile, 'images', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult = $this->fileManagerService->uploadImage($price->title.'-m', $request->cropped_data_mobile, 'images/thumbnail', $imageManagerThumbnail);
                if($thumbnailResult == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old files if there is.
                 */
                if (file_exists($price->image_small_screen)) {
                    unlink($price->image_small_screen);
                }
                if (file_exists($price->thumbnail_small_screen)) {
                    unlink($price->thumbnail_small_screen);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $price->image_small_screen = $result;
                $price->thumbnail_small_screen = $thumbnailResult;
                if ($price->save()) {
                    $request->session()->flash('success', 'Ucret Güncellendi');
                } else {
                    $request->session()->flash('error', 'Ucret Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_small');                 //Which tab will be opened.
            return back();
        }else if($request->has('updatepricingsettings')){

            $price->title = $request->title;
            $price->subtitle = $request->subtitle;
            $price->keywords = $request->keywords;
            $price->description = $request->description;
            $price->pricing_url = $request->pricing_url;
            $price->video_embed_code = $request->video_embed_code;
            $price->entry1 = $request->entry1;
            $price->entry2 = $request->entry2;
            $price->entry3 = $request->entry3;
            $price->entry4 = $request->entry4;
            $price->entry5 = $request->entry5;
            $price->entry6 = $request->entry6;
            $price->entry7 = $request->entry7;
            $price->entry8 = $request->entry8;
            $price->entry9 = $request->entry9;
            $price->entry10 = $request->entry10;

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Updating slug with old slug number.
             */
            $price->slug = Str::slug($request->title.'-'.last(explode('-',$price->slug)));

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Switch tool returns 'on' or null.
             */
            if ('on' == $request->is_active) {
                $price->is_active = 1;
            } else {
                $price->is_active = 0;
            }
            if ($price->save()) {
                $request->session()->flash('success', 'Başarıla Güncellendi');
            } else {
                $request->session()->flash('error', 'Güncelleme Başarısız');
            }
            session()->flash('tab_page','context');                 //Which tab will be opened.
            return back();
        }else if($request->has('updatepricingorder')){

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Returns locations of announcement's on order and re-assigns orders in order to that locations.
             */
            $orders = explode(',',$request->result_h_flow);
            $prices = Pricing::where('lang_code',$request->lang_code)->orderBy('order','asc')->get();
            $i=0;
            foreach($orders as $order){
                $prices[$order]->order = $i+1;
                $prices[$order]->save();
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
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Re-order after deleting process.
         */
        $price = Pricing::findOrFail($id);
        $lang_code = $price->lang_code;
        foreach (Pricing::where('lang_code', $price->lang_code)->where('order', '>', $price->order)->get() as $o_item) {
            $o_item->order = $o_item->order - 1;
            $o_item->save();
        }
        $price->delete();
        session()->flash('success', 'Deleted');
        return redirect()->route('admin.pricing-settings.index', ['lang_code' => $lang_code]);
    }
}
