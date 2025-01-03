<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\StylistLoginController;
use App\Http\Controllers\StylistController;

// スタイリスト
Route::middleware(['web'])->group(function () {
  //スタイリスト用ログイン
  Route::get('login', [StylistLoginController::class, 'showLoginForm'])->name('login');
  Route::post('login', [StylistLoginController::class, 'login']);
  Route::post('logout', [StylistLoginController::class, 'logout'])->name('logout');

  Route::get('home', [StylistController::class, 'home'])->name('home');
});
