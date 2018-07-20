<?php

namespace App\Http\Middleware;

use Closure;

class LoginMiddleWare
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
      session(['id'=>""]);
        if(session('id'))
        {

            return $next($request);
        }else{
            echo  "未登录";die;
            return redirect('/admin/login/login');
        }
    }
}
