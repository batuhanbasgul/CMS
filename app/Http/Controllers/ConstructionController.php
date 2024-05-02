<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Construction;
use App\Models\ImageManager;
use App\Models\Language;
use App\Services\FileManagerService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class ConstructionController extends Controller
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

        if(0 == Construction::all()->count()){
            return view('admin.construction-add', [
                'lang_code'=>session('lang_code'),
                'langs' => Language::orderBy('order','asc')->get()
            ]);
        }else{
            if(0 == count(Construction::where('lang_code',$request->lang_code)->get())){
                return view('admin.construction-add', [
                    'lang_code'=>$request->lang_code,
                    'langs' => Language::orderBy('order','asc')->get()
                ]);
            }else{
                return view('admin.construction-update', [
                    'content' => Construction::where('lang_code',$request->lang_code)->first(),
                    'page' => $request->page,
                    'image_size' => ImageManager::where('type','yapım_aşaması')->first(),
                    'lang_code' => $request->lang_code,
                    'langs' => Language::orderBy('order','asc')->get()
                ]);
            }
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
            return redirect()->route('admin.construction.index', [
                'page' => $request->page,
                'lang_code' => $request->lang_code
            ]);
        }

        $content = new Construction();
        $content->title = $request->title;
        $content->description = $request->long_description;
        $content->short_description = $request->short_description;
        $content->start_date = $request->start_date;
        $content->color = $request->color;
        $content->lang_code = $request->lang_code;
        if($content->save()){
            $request->session()->flash('success','Kaydetme Başarılı.');
        }else{
            $request->session()->flash('error','Kaydetme Başarısız.');
        }
        return redirect()->route('admin.construction.index', [
            'page' => $request->page,
            'lang_code' => $request->lang_code
        ]);
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
        $content = Construction::findOrFail($id);
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
            if ($request->cropped_data) {
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                * Quality, fize size, image sizes.
                */
                $imageManager = ImageManager::where('type', 'yapım_aşaması')->first();
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Uploading image from FileManagerService. Returns file path.
                 */
                $result = $this->fileManagerService->uploadImage($content->title,$request->cropped_data,'images',$imageManager);
                if($result == '0'){
                    $request->session()->flash('file_size_error', 'Dosya boyutu.');
                    return back();
                }

                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Delete old file if there is one.
                 */
                if (file_exists($content->image)) {
                    unlink($content->image);
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Saving image's file path.
                 */
                $content->image = $result;
                if ($content->save()) {
                    $request->session()->flash('success', 'Ürün Güncellendi');
                } else {
                    $request->session()->flash('error', 'Ürün Güncellenemedi');
                }
            }
            session()->flash('tab_page','page_image_large');    //Which tab will be opened.
            return back();
        }else if($request->has('updateconstruction')){
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

            $content->title = $request->title;
            $content->description = $request->long_description;
            $content->short_description = $request->short_description;
            $content->start_date = $request->start_date;
            $content->color = $request->color;

            if ('on' == $request->is_active) {
                $content->is_active = 1;
            } else {
                $content->is_active = 0;
            }

            if ($content->save()) {
                $request->session()->flash('success', 'Başarıyla Güncellendi');
            } else {
                $request->session()->flash('error', 'Güncelleme Başarısız');
            }
            session()->put('construction', $content->is_active);
            session()->flash('tab_page','page_content');    //Which tab will be opened.
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
