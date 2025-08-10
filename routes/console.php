<?php

use Illuminate\Support\Facades\Schedule;

// スケジュールの登録
Schedule::command('app:generate-schedule')->weeklyOn(0, '00:00');
