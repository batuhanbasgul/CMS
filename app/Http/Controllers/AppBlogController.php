<?php

namespace App\Http\Controllers;

use App\Models\AppBlog;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class AppBlogController extends Controller
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
         * if not null and there is content for that language, reroutes to edit page with lang_code, blog content, langs parameters.
         */
        if (0 == AppBlog::all()->count()) {
            return view('admin.app-blog-settings-add', ['lang_code' => session('lang_code'), 'langs' => Language::all()]); //Parameter came from nav link
        } else {
            $appBlog = AppBlog::where('lang_code', $request->lang_code)->get();
            if (0 == $appBlog->count()) {
                return view('admin.app-blog-settings-add', ['lang_code' => $request->lang_code, 'langs' => Language::all()]);
            }
            return view('admin.app-blog-settings-update', [
                'lang_code' => $request->lang_code,
                'content' => $appBlog[0],
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

        $appBlog = new AppBlog();
        $appBlog->title = $request->title;
        $appBlog->subtitle = $request->subtitle;
        $appBlog->description = $request->description;
        $appBlog->short_description = $request->short_description;
        $appBlog->lang_code = $request->lang_code;

        if($request->blog_count){
            $appBlog->blog_count = $request->blog_count;
        }else{
            $appBlog->blog_count = 3;
        }

        if ('on' == $request->is_active) {
            $appBlog->is_active = 1;
        } else {
            $appBlog->is_active = 0;
        }

        if ($appBlog->save()) {
            $request->session()->flash('success', 'Kayıt Başarılı');
        } else {
            $request->session()->flash('error', 'Kayıt Başarısız');
        }
        return redirect()->route('admin.app-blog-settings.index', ['lang_code' => $request->lang_code]);
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
        $appBlog = AppBlog::findOrFail($id);

        $appBlog->title = $request->title;
        $appBlog->subtitle = $request->subtitle;
        $appBlog->description = $request->description;
        $appBlog->short_description = $request->short_description;

        if($request->blog_count){
            $appBlog->blog_count = $request->blog_count;
        }else{
            $appBlog->blog_count = 3;
        }

        if ('on' == $request->is_active) {
            $appBlog->is_active = 1;
        } else {
            $appBlog->is_active = 0;
        }

        if ($appBlog->save()) {
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
