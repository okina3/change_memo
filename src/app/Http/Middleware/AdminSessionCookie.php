<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminSessionCookie
{
   /**
    * 管理画面用のセッション cookie 名を切り替えるミドルウェア。
    *
    * 目的：
    * 同一アプリでユーザー用と管理者用のセッションを分けたい場合に使用。
    * 同一ブラウザでの管理画面と通常画面のセッション分離が可能。
    */
   public function handle(Request $request, Closure $next)
   {
      if ($request->is('admin*')) {
         config(['session.cookie' => config('session.cookie_admin')]);
      }

      return $next($request);
   }
}
