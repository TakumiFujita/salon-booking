<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\StylistController;

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');

Route::middleware('auth')->group(function () {
  Route::get('/user/reservation/home', [ReservationController::class, 'home'])->name('reservation.home');
  // リダイレクト処理用のルート
  Route::get('/user/reservation/redirect', [ReservationController::class, 'redirect'])->name('reservation.redirect');
  Route::get('/get-schedule', [ReservationController::class, 'getSchedule']);
  Route::get('/user/reservation/confirmation', [ReservationController::class, 'confirmation'])->name('reservation.confirmation');
  Route::post('/user/reservation/store', [ReservationController::class, 'store'])->name('reservation.store');
});

// ログイン・ログアウトのルート定義
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'login'])->middleware('guest');
Route::post('logout', [LogoutController::class, 'logout'])->name('logout');

Route::post('/checkout-payment', [StripeController::class, 'checkout'])->name('checkout.session');

Route::prefix('stylist')->name('stylist.')->group(function () {
  //スタイリスト用ログイン
  Route::get('login', [StylistController::class, 'showLoginForm'])->name('login');
  Route::post('login', [StylistController::class, 'login']);
  Route::post('logout', [StylistController::class, 'logout'])->name('logout');

  Route::get('home', [StylistController::class, 'home'])->name('home');
});
