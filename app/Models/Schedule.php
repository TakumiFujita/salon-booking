<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['stylist_id', 'start_time', 'end_time', 'status'];

    /**
     * 指定した期間のスケジュールを作成する
     */
    public static function generateSchedule(Carbon $startDate, Carbon $endDate, int $stylistId = 1)
    {
        $timeSlots = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            // 既存スケジュールの有無をチェック（バッチ処理の場合のみ適用）
            if (self::whereDate('start_time', $date->toDateString())->exists()) {
                continue;
            }

            // 9:00〜20:00まで30分刻みで時間枠を作成
            for ($hour = 9; $hour < 20; $hour++) {
                foreach ([0, 30] as $minute) {
                    $startTime = Carbon::create($date->year, $date->month, $date->day, $hour, $minute);
                    $endTime = $startTime->copy()->addMinutes(30);

                    $timeSlots[] = [
                        'stylist_id' => $stylistId,
                        'start_time' => $startTime->toDateTimeString(),
                        'end_time' => $endTime->toDateTimeString(),
                        'status' => 'available',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // バルクインサートで一括挿入
        self::insert($timeSlots);
    }
}
