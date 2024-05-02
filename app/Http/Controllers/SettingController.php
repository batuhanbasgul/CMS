<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\App;

class SettingController extends Controller
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
        //
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
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     */
    public function edit(Request $request)
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
         * Auto generate settings data if there is none.
         */
        if (0 == Setting::all()->count()) {
            $setting = new Setting();
            $setting->save();
        }

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * If target user has equal or lower authority then go to settings page.
         */
        if (Gate::allows('crud-show', User::findOrFail($request->user_id))) {
            if($request->setting == 'maintenance'){
                /**
                 * Sidebar selected item
                 */
                session()->forget('selectedSideMenu');
                session()->put('selectedSideMenu','maintenance');
                return view('admin.settings-maintenance', ['settings' => Setting::first()]);
            }else if($request->setting == 'theme'){
                return view('admin.settings', ['user' => User::find($request->user_id)]);
            }
        } else {
            return redirect()->route('admin.index');
        }
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
        if ($request->has('updateuserpreferences')) {
            $user = User::findOrFail($id);
            $user->theme_dark = $request->theme_dark;
            if ($user->save()) {
                $request->session()->flash('success', 'Ayarlar Kaydedildi');
            } else {
                $request->session()->flash('error', 'Ayarlar Kaydedilemedi');
            }
            session()->forget('theme_dark');
            session()->put('theme_dark', $user->theme_dark);
            return back();
        } else if ($request->has('updatemaintenance')) {
            $setting = Setting::findOrFail($id);
            if ($request->maintenance_app == 'on') {
                $setting->maintenance_app = 1;
            } else {
                $setting->maintenance_app = 0;
            }
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Only user that has master authority can make maintenance_panel true. Authorization gate is on blade side.
             */
            if ($request->maintenance_panel == 'on') {
                $setting->maintenance_panel = 1;
            } else {
                $setting->maintenance_panel = 0;
            }
            $setting->save();
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Notification
             */
            if($setting->maintenance_app || $setting->maintenance_panel){
                session()->put('maintenance', true);
            }else{
                session()->put('maintenance', false);
            }
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
