<?php

namespace App\Http\Controllers;

use App\Models\AppReference;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class appReferenceController extends Controller
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
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Sidebar selected tab.
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
         * if not null and there is content for that language, reroutes to edit page with lang_code, reference content, langs parameters.
         */
        if (0 == AppReference::all()->count()) {
            return view('admin.app-reference-settings-add', ['lang_code' => session('lang_code'), 'langs' => Language::all()]); //Parameter came from nav link
        } else {
            $appReference = AppReference::where('lang_code', $request->lang_code)->get();
            if (0 == $appReference->count()) {
                return view('admin.app-reference-settings-add', ['lang_code' => $request->lang_code, 'langs' => Language::all()]);
            }
            return view('admin.app-reference-settings-update', [
                'lang_code' => $request->lang_code,
                'content' => $appReference[0],
                'langs' => Language::orderBy('order','asc')->get()
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

        $appReference = new AppReference();
        $appReference->title = $request->title;
        $appReference->subtitle = $request->subtitle;
        $appReference->description = $request->description;
        $appReference->short_description = $request->short_description;
        $appReference->lang_code = $request->lang_code;

        if($request->reference_count){
            $appReference->reference_count = $request->reference_count;
        }else{
            $appReference->reference_count = 6;
        }

        if ('on' == $request->is_active) {
            $appReference->is_active = 1;
        } else {
            $appReference->is_active = 0;
        }

        if ($appReference->save()) {
            $request->session()->flash('success', 'Kayıt Başarılı');
        } else {
            $request->session()->flash('error', 'Kayıt Başarısız');
        }
        return redirect()->route('admin.app-reference-settings.index', ['lang_code' => $request->lang_code]);
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
        $appReference = AppReference::findOrFail($id);

        $appReference->title = $request->title;
        $appReference->subtitle = $request->subtitle;
        $appReference->description = $request->description;
        $appReference->short_description = $request->short_description;

        if($request->reference_count){
            $appReference->reference_count = $request->reference_count;
        }else{
            $appReference->reference_count = 6;
        }

        if ('on' == $request->is_active) {
            $appReference->is_active = 1;
        } else {
            $appReference->is_active = 0;
        }

        if ($appReference->save()) {
            $request->session()->flash('success', 'Kayıt Başarılı');
        } else {
            $request->session()->flash('error', 'Kayıt Başarısız');
        }
        return back();
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
