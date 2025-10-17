<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
   /**
    * Get the path the user should be redirected to when they are not authenticated.
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
