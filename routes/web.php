<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\StripeController;

// ユーザー
Route::prefix('user')->name('user.')->middleware('auth:web')->group(function () {
    Route::prefix('reservation')->name('reservation.')->group(function () {
        Route::get('/home', [ReservationController::class, 'home'])->name('home');
        Route::get('/index', [ReservationController::class, 'index'])->name('index');
        // リダイレクト処理用のルート
        Route::get('/redirect', [ReservationController::class, 'redirect'])->name('redirect');
        Route::get('/confirmation', [ReservationController::class, 'confirmation'])->name('confirmation');
        Route::post('/store', [ReservationController::class, 'store'])->name('store');
    });

    Route::get('get-schedule', [ReservationController::class, 'getSchedule']);
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

// ログイン・ログアウトのルート定義
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'login'])->middleware('guest');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('register', [RegisterController::class, 'register'])->middleware('guest');

Route::get('/email/verify', [EmailVerificationController::class, 'showNotice'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware('auth', 'signed')->name('verification.verify');
Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Stripe決済
Route::post('/checkout-payment', [StripeController::class, 'checkout'])->name('checkout.session');
