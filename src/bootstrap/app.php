<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // マルチログイン用のSession Cookie 名設定
        $middleware->prependToGroup('web', \App\Http\Middleware\AdminSessionCookie::class);

        // 認証ミドルウェアのエイリアス差し替え（未認証時のリダイレクト先を URL に応じて分岐）
        // - 既定の Illuminate\Auth\Middleware\Authenticate を、プロジェクト独自版に置換
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
