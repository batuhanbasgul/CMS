<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

class AppMaintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(0 == Setting::all()->count()){
            $setting = new Setting();
            $setting->maintenance_app = 0;
            $setting->maintenance_panel = 0;
            $setting->save();
        }
        $settings = Setting::first();
        if (Gate::allows('admin') || Gate::allows('master')){
            return $next($request);
        }else if($settings->maintenance_app){
            Auth::logout();
            return redirect()->route('maintenance-app');
        }else{
            return $next($request);
        }
    }
}
