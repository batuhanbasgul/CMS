<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
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

        $user = User::findOrFail(Auth::id());
        if ($user->is_active){
            return $next($request);
        }else{
            if($request->logout){ Auth::logout(); }
            return redirect()->route('user-passive');
        }
    }
}
