<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Language;
use App\Models\ImageManager;
use App\Services\FileManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;

class GallerySettingsController extends Controller
{
    //GALLERY HOLDS GENERAL PURPPOSE IMAGES, PRODUCTS&ANNOUNCEMENTS HAS THEIR OWN IMAGES THAT HAS MORE SPESIFIC FEATURES.

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
     * @param \Illuminate\Http\Rquest $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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

        if($request->refresh){
            return back();
        }
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * On gallery page, routes to gallery,
         * While routing calls images in order to lang code and menu code.
         * Routes to gallery index page with images, langs and menu code parameters.
         */
        $images = Gallery::where('lang_code', $request->lang_code)->where('menu_code', $request->menu_code)->get();
        $langs = Language::orderBy('order','asc')->get();
        return view('admin.gallery-settings', ['images' => $images, 'langs' => $langs, 'menu_code' => $request->menu_code]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Rquest $request
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
         * Routes to add page with lang code and content's menu code.
         * Created image has these info.
         * Image size data comes from ImageManager settings.
         */
        return view('admin.gallery-settings-add', [
            'menu_code' => $request->menu_code,
            'lang_code' => $request->lang_code,
            'langs' => Language::all(),
            'image_size' => ImageManager::where('type','galeri_resim')->first(),
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
         * Create the data.
         */
        $gallery = new Gallery();

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Validation for file mime from FileManagerService.
         */
        if($request->hasFile('image')){
            if(!$this->fileManagerService->checkExtension($request->image)){
                $request->session()->flash('file_extension_error','Dosya uzantısı.');
                return back();
            }
        }else{
            $request->session()->flash('no_image_error','Resim yüklenmedi');
            return back()->withInput();
        }

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Upload cropped image if there is one.
         */
        if ($request->cropped_data) {
            /** ►►►►► DEVELOPER ◄◄◄◄◄
            * Quality, fize size, image sizes.
            */
            $imageManager = ImageManager::where('type', 'galeri_resim')->first();
            $imageManagerThumbnail = ImageManager::where('type','galeri_thumbnail')->first();

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Uploading image from FileManagerService. Returns file path.
             */
            $result = $this->fileManagerService->uploadImage($request->title,$request->cropped_data,'gallery',$imageManager);
            if($result == '0'){
                $request->session()->flash('file_size_error', 'Dosya boyutu.');
                return back();
            }
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Uploading thumbnail image from FileManagerService. Returns file path.
             */
            $thumbnailResult = $this->fileManagerService->uploadImage($request->title,$request->cropped_data,'gallery/thumbnail',$imageManagerThumbnail);
            if($thumbnailResult == '0'){
                $request->session()->flash('file_size_error', 'Dosya boyutu.');
                return back();
            }
            $gallery->image = $result;
            $gallery->thumbnail = $thumbnailResult;
        } else {
            $request->session()->flash('no_image_error','Resim yüklenmedi');
            return back()->withInput();
        }
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Image's other data.
         */
        $gallery->title = $request->title;
        $gallery->description = $request->description;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * menu_code spesifies gallary image's content.
         * lang_code is Language.
         */
        $gallery->menu_code = $request->menu_code;
        $gallery->lang_code = $request->lang_code;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Add to last in order.
         */
        $order = 0;
        foreach (Gallery::where('lang_code', $request->lang_code)->where('menu_code', $request->menu_code)->get() as $a) {
            if ($a->order > $order) {
                $order = $a->order;
            }
        }
        $gallery->order = $order + 1;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * SLUG generating with image title and ID + 1.
         */
        if (0 < Gallery::where('lang_code', $request->lang_code)->orderBy('id', 'desc')->get()->count()) {
            $slugCount = Gallery::where('lang_code', $request->lang_code)->orderBy('id', 'desc')->get()->first()->id + 1;
        } else {
            $slugCount = 1;
        }
        $gallery->slug = Str::slug($slugCount . '-' . $request->title);

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Saving image's file path.
         */
        if ($gallery->save()) {
            $request->session()->flash('success', 'Kayıt Başarılı');
        } else {
            $request->session()->flash('error', 'Kayıt Başarısız');
        }
        return redirect()->route('admin.gallery-settings.index', ['menu_code' => $request->menu_code, 'lang_code' => $request->lang_code])->withInput();
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
     * Order update page.
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

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Check if there is image on that language and content(menu_code).
         */
        $galleries = Gallery::where('lang_code', $request->lang_code)->where('menu_code', $request->menu_code)->orderBy('order','asc')->get();
        if(count($galleries) == 0){
            $request->session()->flash('no_image','Resim Kaydı Bulunamadı');
            return back();
        }
        return view('admin.gallery-settings-update-order', [
            'galleries' => $galleries,
            'langs' => Language::all(),
        ]);
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
         * Selected Image - $image
         * All Images on that language and content - $images
         * All Languages - $langs
         * Selected Image's size info. - $image_size
         * Selected Image's language. - $lang_code
         */
        $image = Gallery::findOrFail($id);
        $images = Gallery::where('lang_code', $image->lang_code)->where('menu_code', $image->menu_code)->orderBY('order', 'asc')->get();
        return view('admin.gallery-settings-update', [
            'image' => $image,
            'images' => $images,
            'langs' => Language::all(),
            'image_size' => ImageManager::where('type','galeri_resim')->first(),
            'lang_code' => $image->lang_code
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Call the data.
         */
        $gallery = Gallery::findOrFail($id);

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Two different forms.
         */
        if($request->has('updateimagelarge')){
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Validation for file mime from FileManagerService.
             */
            if($request->hasFile('image')){
                if(!$this->fileManagerService->checkExtension($request->image)){
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
                $imageManager = ImageManager::where('type', 'galeri_resim')->first();
                $imageManagerThumbnail = ImageManager::where('type','galeri_thumbnail')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($gallery->title,$request->cropped_data,'gallery',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult = $this->fileManagerService->uploadImage($gallery->title,$request->cropped_data,'gallery/thumbnail',$imageManagerThumbnail);
                if($thumbnailResult == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old files if there is.
                 */
                if (file_exists($gallery->image)) {
                    unlink($gallery->image);
                }
                if (file_exists($gallery->thumbnail)) {
                    unlink($gallery->thumbnail);
                }
                $gallery->image = $result;
                $gallery->thumbnail = $thumbnailResult;
            }

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Image's other data.
             */
            $gallery->title = $request->title;
            $gallery->description = $request->description;

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Updating SLUG with old slug no and new image title.
             */
            $gallery->slug = Str::slug(substr($gallery->slug, 0, 1) . '-' . $request->title);

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Switch returns 1 on 'on', null on 'off'.
             */
            if ('on' == $request->is_active) {
                $gallery->is_active = 1;
            } else {
                $gallery->is_active = 0;
            }

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Saving image's data.
             */
            if ($gallery->save()) {
                $request->session()->flash('success', 'Güncelleme Başarılı');
            } else {
                $request->session()->flash('error', 'Güncelleme Başarısız');
            }
            session()->flash('tab_page','image_data');                 //Which tab will be opened.
            return back();
        }else if($request->has('updategalleryorder')){

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Returns locations of announcement's on order and re-assigns orders in order to that locations.
             */
            $orders = explode(',',$request->result_h_flow);
            $galleries = Gallery::where('lang_code',$request->lang_code)->where('menu_code',$request->menu_code)->orderBy('order','asc')->get();
            $i=0;
            foreach($orders as $order){
                $galleries[$order]->order = $i+1;
                $galleries[$order]->save();
                $i++;
            }

            session()->flash('tab_page','image_order');                 //Which tab will be opened.
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
        $image = Gallery::findOrFail($id);
        $menu_code = $image->menu_code;
        $lang_code = $image->lang_code;
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Re-order after deleting process.
         */
        foreach (Gallery::where('lang_code', $lang_code)->where('menu_code', $menu_code)->where('order', '>', $image->order)->get() as $o_item) {
            $o_item->order = $o_item->order - 1;
            $o_item->save();
        }
        $image->delete();
        session()->flash('success', 'Deleted');
        return redirect()->route('admin.gallery-settings.index', ['menu_code' => $menu_code, 'lang_code' => $lang_code]);
    }
}
