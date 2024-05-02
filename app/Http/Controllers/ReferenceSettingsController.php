<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Reference;
use App\Models\ImageManager;
use App\Services\FileManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class ReferenceSettingsController extends Controller
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
            return view('admin.reference-settings', [
                'langs' => Language::orderBy('order','asc')->get(),
                'references' => Reference::where('lang_code', $request->lang_code)->get(),
                'lang_code' => $request->lang_code
            ]);
        } else {
            return view('admin.reference-settings', [
                'langs' => Language::orderBy('order','asc')->get(),
                'references' => Reference::where('lang_code', session('lang_code'))->get(),
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
        return view('admin.reference-settings-add', ['lang_code' => $request->lang_code, 'langs' => Language::all()]);
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
        $reference = new Reference();
        $reference->title = $request->title;
        $reference->description = $request->description;
        $reference->keywords = $request->keywords;
        $reference->video_embed_code = $request->video_embed_code;
        $reference->lang_code = $request->lang_code;
        $reference->reference_url = $request->ref_link;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * On same content but different languages has same "menu_code" for matching.
         * UID for just one menu and one content.
         */
        if (0 < Reference::where('lang_code', $request->lang_code)->orderBy('id', 'desc')->get()->count()) {
            $slugCount = Reference::where('lang_code', $request->lang_code)->orderBy('id', 'desc')->get()->first()->id + 1;
        } else {
            $slugCount = 1;
        }
        $reference->slug = Str::slug($request->title. '-' .$slugCount);

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Add to last order.
         */
        $order = 0;
        foreach (Reference::where('lang_code', $request->lang_code)->get() as $a) {
            if ($a->order > $order) {
                $order = $a->order;
            }
        }
        $reference->order = $order + 1;

        if ($reference->save()) {
            $request->session()->flash('success', 'Başarıla oluşturuldu');
            return redirect()->route('admin.reference-settings.edit', [$reference->id,'lang_code' => $request->lang_code,'langs' => Language::all()])->withInput();
        } else {
            $request->session()->flash('error', 'Kaydetme Başarısız');
            return redirect()->route('admin.reference-settings.add', ['lang_code' => $request->lang_code,'langs' => Language::all()])->withInput();
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

        $references = Reference::where('lang_code', $request->lang_code)->orderBy('order','asc')->get();
        if(count($references) == 0){
            $request->session()->flash('no_reference','Referans Kaydı Bulunamadı');
            return back();
        }
        return view('admin.reference-settings-update-order', [
            'references' => $references,
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

        $reference = Reference::findOrFail($id);
        $references = Reference::where('lang_code', $reference->lang_code)->orderBy('order', 'asc')->get();
        return view('admin.reference-settings-update', [
            'reference' => $reference,
            'references' => $references,
            'page' => $request->page,
            'langs' => Language::all(),
            'large_size' => ImageManager::where('type','referans_resim_geniş')->first(),
            'mobile_size' => ImageManager::where('type','referans_resim_mobil')->first()
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
        $reference = Reference::findOrFail($id);

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
                $imageManager = ImageManager::where('type', 'referans_resim_geniş')->first();
                $imageManagerThumbnail = ImageManager::where('type','referans_thumbnail_geniş')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($reference->title.'-l', $request->cropped_data, 'images', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult = $this->fileManagerService->uploadImage($reference->title.'-l', $request->cropped_data, 'images/thumbnail', $imageManagerThumbnail);
                if($thumbnailResult == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old files if there is.
                 */
                if (file_exists($reference->image_large_screen)) {
                    unlink($reference->image_large_screen);
                }
                if (file_exists($reference->thumbnail_large_screen)) {
                    unlink($reference->thumbnail_large_screen);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $reference->image_large_screen = $result;
                $reference->thumbnail_large_screen = $thumbnailResult;
                if ($reference->save()) {
                    $request->session()->flash('success', 'Referans Güncellendi');
                } else {
                    $request->session()->flash('error', 'Referans Güncellenemedi');
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
                $imageManager = ImageManager::where('type', 'referans_resim_mobil')->first();
                $imageManagerThumbnail = ImageManager::where('type','referans_thumbnail_mobil')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($reference->title.'-m', $request->cropped_data_mobile, 'images', $imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading thumbnail image from FileManagerService. Returns file path.
                 */
                $thumbnailResult = $this->fileManagerService->uploadImage($reference->title.'-m', $request->cropped_data_mobile, 'images/thumbnail', $imageManagerThumbnail);
                if($thumbnailResult == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old files if there is.
                 */
                if (file_exists($reference->image_small_screen)) {
                    unlink($reference->image_small_screen);
                }
                if (file_exists($reference->thumbnail_small_screen)) {
                    unlink($reference->thumbnail_small_screen);
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $reference->image_small_screen = $result;
                $reference->thumbnail_small_screen = $thumbnailResult;
                if ($reference->save()) {
                    $request->session()->flash('success', 'Referans Güncellendi');
                } else {
                    $request->session()->flash('error', 'Referans Güncellenemedi');
                }
            }
            session()->flash('tab_page','image_small');                 //Which tab will be opened.
            return back();
        }else if($request->has('updatereferencesettings')){

            $reference->title = $request->title;
            $reference->keywords = $request->keywords;
            $reference->description = $request->description;
            $reference->reference_url = $request->ref_link;
            $reference->video_embed_code = $request->video_embed_code;

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Updating slug with old slug number.
             */
            $reference->slug = Str::slug($request->title.'-'.last(explode('-',$reference->slug)));

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Switch tool returns 'on' or null.
             */
            if ('on' == $request->is_active) {
                $reference->is_active = 1;
            } else {
                $reference->is_active = 0;
            }
            if ($reference->save()) {
                $request->session()->flash('success', 'Başarıla Güncellendi');
            } else {
                $request->session()->flash('error', 'Güncelleme Başarısız');
            }
            session()->flash('tab_page','context');                 //Which tab will be opened.
            return back();
        }else if($request->has('updatereferencesorder')){

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Returns locations of announcement's on order and re-assigns orders in order to that locations.
             */
            $orders = explode(',',$request->result_h_flow);
            $references = Reference::where('lang_code',$request->lang_code)->orderBy('order','asc')->get();
            $i=0;
            foreach($orders as $order){
                $references[$order]->order = $i+1;
                $references[$order]->save();
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
        $reference = Reference::findOrFail($id);
        $lang_code = $reference->lang_code;
        foreach (Reference::where('lang_code', $reference->lang_code)->where('order', '>', $reference->order)->get() as $o_item) {
            $o_item->order = $o_item->order - 1;
            $o_item->save();
        }
        $reference->delete();
        session()->flash('success', 'Deleted');
        return redirect()->route('admin.reference-settings.index', ['lang_code' => $lang_code]);
    }
}
