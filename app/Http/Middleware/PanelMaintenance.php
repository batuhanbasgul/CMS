<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class PanelMaintenance
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
        if(Gate::allows('master') || !$settings->maintenance_panel){
            return $next($request);    
        }else{
            Auth::logout();
            return redirect()->route('maintenance-panel');
        }
    }
}
