<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Slider;
use App\Models\ImageManager;
use App\Services\FileManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class SliderSettingsController extends Controller
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
         * if not null and there is content for that language, reroutes to edit page with lang_code, references, langs parameters.
         */
        if ($request->lang_code) {
            return view('admin.slider-settings', ['langs' => Language::orderBy('order','asc')->get(), 'sliders' => Slider::where('lang_code', $request->lang_code)->get(), 'lang_code' => $request->lang_code]);
        } else {
            return view('admin.slider-settings', ['langs' => Language::orderBy('order','asc')->get(), 'sliders' => Slider::where('lang_code', session('lang_code'))->get(), 'lang_code' => session('lang_code')]);
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

        return view('admin.slider-settings-add', ['lang_code' => $request->lang_code, 'langs' => Language::all()]);
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

        $slider = new Slider();
        $slider->title = $request->title;
        $slider->description = $request->description;
        $slider->link = $request->link;
        $slider->video_embed_code = $request->video_embed_code;
        $slider->logo = $request->logo;
        $slider->logo_title = $request->logo_title;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Switch tool returns 'on' or null.
         */
        if ('on' == $request->is_desktop_active) {
            $slider->is_desktop_active = 1;
        } else {
            $slider->is_desktop_active = 0;
        }
        if ('on' == $request->is_mobile_active) {
            $slider->is_mobile_active = 1;
        } else {
            $slider->is_mobile_active = 0;
        }

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Add to last order.
         */
        $order = 0;
        foreach (Slider::where('lang_code', $request->lang_code)->get() as $a) {
            if ($a->order > $order) {
                $order = $a->order;
            }
        }
        $slider->order = $order + 1;

        $slider->lang_code = $request->lang_code;
        if ($slider->save()) {
            $request->session()->flash('success', 'Başarıla oluşturuldu');
            return redirect()->route('admin.slider-settings.index', ['lang_code' => $request->lang_code])->withInput();
        } else {
            $request->session()->flash('error', 'Kaydetme Başarısız');
            return redirect()->route('admin.slider-settings.add', ['lang_code' => $request->lang_code])->withInput();
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

        $sliders = Slider::where('lang_code', $request->lang_code)->orderBy('order','asc')->get();
        if(count($sliders) == 0){
            $request->session()->flash('no_slider','Slider Kaydı Bulunamadı');
            return back();
        }
        return view('admin.slider-settings-update-order', [
            'sliders' => $sliders,
            'langs' => Language::all()
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

        $slider = Slider::findOrFail($id);
        $sliders = Slider::where('lang_code', $slider->lang_code)->orderBy('order', 'asc')->get();
        return view('admin.slider-settings-update', [
            'slider' => $slider,
            'sliders' => $sliders,
            'langs' => Language::all(),
            'request' => $request->page,
            'large_size' => ImageManager::where('type','slider_resim_geniş')->first(),
            'mobile_size' => ImageManager::where('type','slider_resim_mobil')->first()
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
        $slider = Slider::findOrFail($id);

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
                $imageManager = ImageManager::where('type', 'slider_resim_geniş')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($slider->title.'-l', $request->cropped_data, 'images', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old files if there is.
                 */
                if (file_exists($slider->image_large_screen)) {
                    unlink($slider->image_large_screen);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $slider->image_large_screen = $result;
                if ($slider->save()) {
                    $request->session()->flash('success', 'Slider Güncellendi');
                } else {
                    $request->session()->flash('error', 'Slider Güncellenemedi');
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
                $imageManager = ImageManager::where('type', 'slider_resim_mobil')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($slider->title.'-m', $request->cropped_data_mobile, 'images', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old files if there is.
                 */
                if (file_exists($slider->image_small_screen)) {
                    unlink($slider->image_small_screen);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $slider->image_small_screen = $result;
                if ($slider->save()) {
                    $request->session()->flash('success', 'Slider Güncellendi');
                } else {
                    $request->session()->flash('error', 'Slider Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_small');                 //Which tab will be opened.
            return back();
        }else if($request->has('updateslidersettings')){
            $slider->title = $request->title;
            $slider->description = $request->description;
            $slider->link = $request->link;
            $slider->video_embed_code = $request->video_embed_code;
            $slider->logo = $request->logo;
            $slider->logo_title = $request->logo_title;

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Switch tool returns 'on' or null.
             */
            if ('on' == $request->is_desktop_active) {
                $slider->is_desktop_active = 1;
            } else {
                $slider->is_desktop_active = 0;
            }

            if ('on' == $request->is_mobile_active) {
                $slider->is_mobile_active = 1;
            } else {
                $slider->is_mobile_active = 0;
            }

            if ($slider->save()) {
                $request->session()->flash('success', 'Başarıla Güncellendi');
            } else {
                $request->session()->flash('error', 'Güncelleme Başarısız');
            }
            session()->flash('tab_page','context');                 //Which tab will be opened.
            return back();
        }else if($request->has('updateslidersorder')){
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Returns locations of announcement's on order and re-assigns orders in order to that locations.
             */
            $orders = explode(',',$request->result_h_flow);
            $sliders = Slider::where('lang_code',$request->lang_code)->orderBy('order','asc')->get();
            $i=0;
            foreach($orders as $order){
                $sliders[$order]->order = $i+1;
                $sliders[$order]->save();
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
        $slider = Slider::findOrFail($id);
        $lang_code = $slider->lang_code;
        foreach (Slider::where('lang_code', $slider->lang_code)->where('order', '>', $slider->order)->get() as $o_item) {
            $o_item->order = $o_item->order - 1;
            $o_item->save();
        }
        $slider->delete();
        session()->flash('success', 'Deleted');
        return redirect()->route('admin.slider-settings.index', ['lang_code' => $lang_code]);
    }
}
