<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageManager;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class ImageManagerController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
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
         * Sidebar selected item
         */
        session()->forget('selectedSideMenu');
        session()->put('selectedSideMenu',last(explode('/',URL::current())));
        /**
         * If the user is 'Master' go to Image Manager Settings or route to index.
         */
        if(Gate::allows('master')){
            return view('admin.image-manager', ['image_settings' => ImageManager::all(), 'current_name' => 'kullanıcı']);
        }else{
            return redirect()->route('admin.index');
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
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        /**
         * If the user is 'Master' execute update process or route to index.
         */
        if(Gate::allows('master')){
            $image_setting = ImageManager::findOrFail($id);
            $image_setting->width = $request->width;
            $image_setting->height = $request->height;
            $image_setting->ratio = $request->width/$request->height;
            $image_setting->file_size = $request->file_size*1024;
            $image_setting->quality = $request->quality;
            $image_setting->save();
            return view('admin.image-manager', ['image_settings' => ImageManager::all(), 'current_name' => explode('_',$image_setting->type)[0]]);
        }else{
            return redirect()->route('admin.index');
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
