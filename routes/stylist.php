<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\StylistLoginController;
use App\Http\Controllers\StylistController;

// スタイリスト用ログインルート（未認証でアクセスできる）
Route::get('login', [StylistLoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [StylistLoginController::class, 'login']);
Route::post('logout', [StylistLoginController::class, 'logout'])->name('logout');

// スタイリスト用のホームなど、認証後にアクセス可能なルート
Route::middleware(['auth:stylist'])->group(function () {
  Route::get('home', [StylistController::class, 'home'])->name('home');
});
