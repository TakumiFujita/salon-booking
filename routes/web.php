<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\StripeController;

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');

Route::middleware('auth')->group(function () {
  Route::get('/user/reservation/home', [ReservationController::class, 'home'])->name('reservation.home');
  Route::get('/get-schedule', [ReservationController::class, 'getSchedule']);
  Route::get('/user/reservation/confirmation', [ReservationController::class, 'confirmation'])->name('reservation.confirmation');
  Route::post('/user/reservation/store', [ReservationController::class, 'store'])->name('reservation.store');
});

// ログイン・ログアウトのルート定義
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'login'])->middleware('guest');
Route::post('logout', [LogoutController::class, 'logout'])->name('logout');

Route::post('/checkout-payment', [StripeController::class, 'checkout'])->name('checkout.session');
