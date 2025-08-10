<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DefaultStylistSchedule;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['stylist_id', 'start_time', 'end_time', 'status'];

    /**
     * 指定した期間のスケジュールを作成する
     */
    public static function generateSchedule(Carbon $startDate, Carbon $endDate)
    {
        $timeSlots = [];
        $defaultSchedule = DefaultStylistSchedule::get(); // スタイリストのデフォルト勤務スケジュールを取得

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            // 曜日ごとの勤務スケジュールを取得
            $daySchedule = $defaultSchedule->where('weekday', $date->dayOfWeek);

            if ($daySchedule->isEmpty()) {
                continue; // その日に勤務予定がない場合はスキップ
            }

            // 勤務時間帯を取得
            foreach ($daySchedule as $shift) {
                $startHour = $shift->start_time->format('H');
                $endHour = $shift->end_time->format('H');

                for ($hour = $startHour; $hour < $endHour; $hour++) {
                    foreach ([0, 30] as $minute) {
                        $startTime = Carbon::create($date->year, $date->month, $date->day, $hour, $minute);
                        $endTime = $startTime->copy()->addMinutes(30);

                        // 重複確認（start_time と end_timeの範囲チェック）
                        $exists = self::where('start_time', $startTime)
                            ->orWhere(function ($query) use ($startTime, $endTime) {
                                $query->where('start_time', '<', $endTime)
                                    ->where('end_time', '>', $startTime);
                            })->exists();

                        if (!$exists) {
                            $timeSlots[] = [
                                'stylist_id' => $shift->stylist_id,
                                'start_time' => $startTime->toDateTimeString(),
                                'end_time' => $endTime->toDateTimeString(),
                                'status' => 'available',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                }
            }
        }

        if (!empty($timeSlots)) {
            self::insert($timeSlots); // バルクインサート
        }
    }
}
