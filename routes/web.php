<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\StripeController;

// ユーザー
Route::prefix('user')->name('user.')->middleware('auth')->group(function () {
  Route::get('reservation/home', [ReservationController::class, 'home'])->name('reservation.home');
  // リダイレクト処理用のルート
  Route::get('reservation/redirect', [ReservationController::class, 'redirect'])->name('reservation.redirect');
  Route::get('reservation/confirmation', [ReservationController::class, 'confirmation'])->name('reservation.confirmation');
  Route::post('reservation/store', [ReservationController::class, 'store'])->name('reservation.store');
  Route::get('get-schedule', [ReservationController::class, 'getSchedule']);
});

// ログイン・ログアウトのルート定義
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'login'])->middleware('guest');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Stripe決済
Route::post('/checkout-payment', [StripeController::class, 'checkout'])->name('checkout.session');
