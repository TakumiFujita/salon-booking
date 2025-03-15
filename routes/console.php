<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// スケジュールの登録
app()->resolving(Schedule::class, function (Schedule $schedule) {
    // 毎週土曜日23:59に5週間後のスケジュールを作成
    $schedule->command('app:generate-schedule')->weeklyOn(6, '23:59');
});
