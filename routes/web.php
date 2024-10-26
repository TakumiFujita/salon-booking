<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ScheduleController;

Route::get('/user/reservation/home', [ReservationController::class, 'home'])->name('reservation.home');
Route::get('/get-schedule', [ReservationController::class, 'getSchedule']);
Route::get('/user/reservation/confirmation', [ReservationController::class, 'confirmation'])->name('reservation.confirmation');
Route::post('/user/reservation/store', [ReservationController::class, 'store'])->name('reservation.store');
