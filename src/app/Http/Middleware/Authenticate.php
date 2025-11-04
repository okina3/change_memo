<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
   /**
    * 未認証時のリダイレクト先を返すミドルウェア。
    *
    * 目的：
    * URLが admin*（管理画面）なら管理者用ログインルート `admin.login` へ
    * それ以外は一般ユーザー用ログインルート `login` へ
    */
   protected function redirectTo($request): ?string
   {
      if ($request->expectsJson()) {
         return null;
      }

      // 未ログイン時のリダイレクト先を分岐
      if ($request->is('admin*')) {
         // 管理者用のログインページへリダイレクト
         return route('admin.login');
      }

      // ユーザー用のログインページへリダイレクト
      return route('login');
   }
}
