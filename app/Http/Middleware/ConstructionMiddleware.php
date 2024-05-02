<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Construction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class ConstructionMiddleware
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
        if(Construction::all()->count() == 0){
            $construction = new Construction();
            $construction->title = 'Sayfa Yapım Aşamasında';
            $construction->lang_code = Lang::getLocale();
            $construction->save();
        }
        $construction = Construction::first();
        if(Auth::id()){
            return $next($request);
        }else{
            if ($construction->is_active){
                return redirect()->route('construction');
            }else{
                return $next($request);
            }
        }
    }
}
