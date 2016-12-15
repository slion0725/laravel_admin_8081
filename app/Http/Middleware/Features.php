<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Menuitem;
use App\Menulink;

class Features
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $menuItem = Menuitem::where('url', explode('/',$request->path())[0] )->first();

        $linkCount = Menulink::where('user_id','=',Auth::id())->where('menu_id','=',$menuItem['id'])->count();

        if($linkCount == 0){
            return redirect('home');
        }

        return $next($request);
    }
}
