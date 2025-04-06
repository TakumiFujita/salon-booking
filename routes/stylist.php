<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\StylistLoginController;
use App\Http\Controllers\StylistController;
use App\Http\Controllers\StylistScheduleController;

// スタイリスト用ログインルート（未認証でアクセスできる）
Route::get('login', [StylistLoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [StylistLoginController::class, 'login']);
Route::post('logout', [StylistLoginController::class, 'logout'])->name('logout');

// スタイリスト用のホームなど、認証後にアクセス可能なルート
Route::middleware(['auth:stylist'])->group(function () {
  Route::get('home', [StylistController::class, 'home'])->name('home');
  Route::name('schedule.')->group(function () {
    Route::get('/schedule/create', [StylistScheduleController::class, 'create'])->name('create');
    Route::post('/schedule/store', [StylistScheduleController::class, 'store'])->name('store');
  });
});

// スタイリスト用のプロフィール編集ルート
Route::name('profile.')->middleware('auth:stylist')->group(function () {
  Route::get('profile/edit', [StylistController::class, 'edit'])->name('edit');
  Route::put('profile/update', [StylistController::class, 'update'])->name('update');
});
