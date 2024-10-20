<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startDate = Carbon::now(); // 現在の日付
        $endDate = Carbon::now()->addWeeks(4); // 4週間分を作成

        $timeSlots = [];

        // スタイリストIDのサンプルとして、1つのIDを仮定
        $stylistId = 1;

        // 各日付に対してスケジュール作成
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            // 9:00〜20:00まで30分刻みで時間枠を作成
            for ($hour = 9; $hour < 20; $hour++) {
                foreach ([0, 30] as $minute) {
                    // 開始時間
                    $startTime = Carbon::create($date->year, $date->month, $date->day, $hour, $minute);
                    // 終了時間は開始時間の30分後
                    $endTime = $startTime->copy()->addMinutes(30);

                    $timeSlots[] = [
                        'stylist_id' => $stylistId, // スタイリストID
                        'start_time' => $startTime->toDateTimeString(), // 開始時間
                        'end_time' => $endTime->toDateTimeString(), // 終了時間
                        'status' => 'available', // 初期状態は予約可能
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // バルクインサートで一括挿入
        DB::table('schedules')->insert($timeSlots);
    }
}
