<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ScheduleController;

Route::get('/user/home', [ReservationController::class, 'home'])->name('home');
Route::get('/get-schedule', [ReservationController::class, 'getSchedule']);
Route::get('/user/confirmation', [ScheduleController::class, 'confirmation'])->name('reservation.confirmation');
