<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\MemoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth:users', 'verified'])->name('dashboard');


Route::prefix('/')->as('user.')->group(function () {
    Route::middleware('auth:users')->group(function () {
        //メモ管理画面
        Route::controller(MemoController::class)->group(function () {
            Route::get('/index', 'index')->name('index');
        });


        // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});
require __DIR__ . '/auth.php';
