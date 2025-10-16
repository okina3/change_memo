<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminSessionCookie
{
   public function handle(Request $request, Closure $next)
   {
      # マルチログイン用のSession Cookie 名設定
      if ($request->is('admin*')) {
         config(['session.cookie' => config('session.cookie_admin')]);
      }
      return $next($request);
   }
}
