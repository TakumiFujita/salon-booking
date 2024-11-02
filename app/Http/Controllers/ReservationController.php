<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use App\Models\Schedule;
use App\Models\Service;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    public function home()
    {
        $services = Service::all();
        $now = Carbon::now();
        $currentMonth = $now->isoFormat('YYYY年MM月');

        // // 1週間分のフォーマットされた日付と曜日を配列に格納
        // $weekDays = [];
        // for ($i = 0; $i < 7; $i++) {
        //     $weekDays[] = $now->copy()->addDays($i);
        // }

        // // 1日分の30分刻みの時間帯を生成するための処理
        // $timeSlots = [];
        // $startTime = Carbon::createFromTime(9, 0); // 9:00から
        // $endTime = Carbon::createFromTime(20, 0);  // 20:00まで
        // while ($startTime->lessThan($endTime)) {
        //     // 9:00から30分刻みで時間を配列に追加
        //     $timeSlots[] = $startTime->format('H:i');
        //     $startTime->addMinutes(30);  // 30分加算
        // }

        // // 今週1週間分のスケジュールデータを取得
        // // `start_time` が今日から1週間分のデータを取得するため、whereBetweenを使用
        // $schedules = Schedule::whereBetween('start_time', [
        //     $now->copy()->startOfDay(), // 今日の開始時間
        //     $now->copy()->addDays(6)->endOfDay() // 6日後の終了時間まで
        // ])->get();

        // // 各日付ごとのスケジュールを格納する配列を初期化
        // $weekSchedules = [];

        // // 各日付（1週間分）と時間帯（9:00〜20:00の30分刻み）ごとに処理
        // foreach ($weekDays as $day) {
        //     LOG::INFO('$day：' . $day);
        //     foreach ($timeSlots as $timeSlot) {
        //         // 該当の日付と時間帯のスケジュールを検索
        //         // データベースにあるstart_timeをフォーマットして、日付と時間が一致するか確認
        //         $schedule = $schedules->first(function ($s) use ($day, $timeSlot) {
        //             return Carbon::parse($s->start_time)->format('Y-m-d H:i') === $day->format('Y-m-d') . ' ' . $timeSlot;
        //         });
        //         LOG::INFO('$schedule->status：' . $schedule->status);
        //         // スケジュールが存在し、statusが 'available' なら ◯、そうでなければ ✗
        //         $weekSchedules[$day->format('Y-m-d')][$timeSlot] = $schedule && $schedule->status === 'available' ? '◯' : '✗';
        //     }
        // }

        // LOG::INFO('$weekSchedules' . json_encode($weekSchedules));

        // $today = $now->format('Y-m-d');
        // $thisWeekSchedules = Schedule::whereDate('start_time', $today)->get();

        // return view('user.reservation.home', compact('services', 'currentMonth', 'weekDays', 'timeSlots', 'weekSchedules', 'schedule'));

        $schedules = $this->getSchedule();
        LOG::INFO('$schedules' . json_encode($schedules));
        // LOG::INFO('$weekSchedules' . json_encode($schedules['weekSchedules']));
        // LOG::INFO('$timeSlots' . json_encode($schedules['timeSlots']));
        // LOG::INFO('$weekDays' . json_encode($schedules['weekDays']));
        return view('user.reservation.home', compact('services', 'currentMonth', 'schedules'));
    }

    public function getSchedule(int $serviceId = 1)
    {
        // $serviceId = $request->get('service_id');
        $service = Service::findOrFail($serviceId);
        $duration = $service->duration;

        // 今日から1週間分のスケジュールを取得
        $weekDays = [];
        $now = Carbon::now();
        for ($i = 0; $i < 7; $i++) {
            $weekDays[] = $now->copy()->addDays($i);
        }

        // LOG::INFO('$weekDays' . json_encode($weekDays));

        // 時間スロットの設定
        $timeSlots = $this->generateTimeSlots();
        $weekSchedules = $this->getWeekSchedules($weekDays, $timeSlots, $serviceId, $duration);

        // dd($weekSchedules);
        // LOG::INFO($weekSchedules);

        // return response()->json($weekSchedules);
        return [
            'weekSchedules' => $weekSchedules,
            'timeSlots' => $timeSlots,
            'weekDays' => $weekDays,
        ];
        // return $weekDays;
    }

    private function generateTimeSlots()
    {
        $timeSlots = [];
        $startTime = Carbon::createFromTime(9, 0);
        $endTime = Carbon::createFromTime(20, 0);
        while ($startTime->lessThan($endTime)) {
            $timeSlots[] = $startTime->format('H:i');
            $startTime->addMinutes(30);
        }
        return $timeSlots;
    }

    private function getWeekSchedules($weekDays, $timeSlots, $serviceId, $duration = null)
    {
        $now = Carbon::now();
        // LOG::INFO('$now' . $now);
        // LOG::INFO('$now->startOfDay()' . $now->startOfDay());
        // LOG::INFO('$now->addDays(6)->endOfDay()' . $now->addDays(6)->endOfDay());
        // $schedules = Schedule::whereBetween('start_time', [
        //     $now->copy()->setTime(9, 0),
        //     $now->addDays(6)->endOfDay()
        // ])->get();

        $start = $now->copy()->setTime(9, 0); // 今日の09:00
        $end = $now->copy()->addDays(6)->setTime(19, 30); // 6日後の19:30

        $schedules = Schedule::whereBetween('start_time', [$start, $end])->get();
        // LOG::INFO('$schedules' . json_encode($schedules));
        // LOG::INFO('$weekDays' . json_encode($weekDays));
        // LOG::INFO('$timeSlots' . json_encode($timeSlots));

        $weekSchedules = [];
        foreach ($weekDays as $day) {
            foreach ($timeSlots as $timeSlot) {
                $schedule = $schedules->first(function ($s) use ($day, $timeSlot) {
                    return Carbon::parse($s->start_time)->format('Y-m-d H:i') === $day->format('Y-m-d') . ' ' . $timeSlot;
                });
                // LOG::INFO('$schedules' . json_encode($schedules));
                // LOG::INFO('$weekSchedules[$day->format('Y-m-d')][$timeSlot]'.$weekSchedules[$day->format('Y-m-d')][$timeSlot]);
                // LOG::INFO('$schedule->status' . $schedule->status);
                // スケジュールが存在するか確認し、利用可能かどうかを判断
                if ($schedule) {
                    $weekSchedules[$day->format('Y-m-d')][$timeSlot] = $schedule->status === 'available' ? '◯' : '✗';
                    // LOG::INFO('$weekSchedules[$day->format("Y-m-d")][$timeSlot]' . $weekSchedules[$day->format("Y-m-d")][$timeSlot]);
                } else {
                    $weekSchedules[$day->format('Y-m-d')][$timeSlot] = 'unavailable';

                    // LOG::INFO('$schedule->status(false)' . $schedule->status);
                }
            }
        }
        // LOG::INFO('$weekSchedules' . json_encode($weekSchedules));

        return $weekSchedules;
    }

    private function isTimeSlotAvailable($start, $end)
    {
        $existingReservations = Schedule::where(function ($query) use ($start, $end) {
            $query->whereBetween('start_time', [$start, $end])
                ->orWhereBetween('end_time', [$start, $end]);
        })->exists();

        return !$existingReservations; // 重複していなければ空いている
    }

    public function confirmation(Request $request)
    {
        // LOG::INFO('confirmation関数の中');
        // LOG::INFO('$request' . $request);
        // LOG::INFO('$request->get("service_id")' . $request->get('service_id'));
        // LOG::INFO('$request->get("date")' . $request->get('date'));
        // LOG::INFO('$request->get("time")' . $request->get('time'));
        $serviceId = $request->get('service_id');
        $date = $request->get('date');
        $startTime = $request->get('time');

        $service = Service::find($serviceId);

        return view('user.reservation.confirmation', compact('service', 'date', 'startTime'));
    }

    public function store(ReservationRequest $request)
    {
        $stylistId = '1';
        $startTime = Carbon::parse($request->input('date') . ' ' . $request->input('start_time'));
        $duration = (int) $request->input('duration');
        $endTime = $startTime->copy()->addMinutes($duration);

        $validatedData = array_merge($request->validated(), [
            'stylist_id' => $stylistId,
            'user_id' => '1',
            'start_time' => $startTime->format('Y-m-d H:i:s'),
            'end_time' => $endTime->format('Y-m-d H:i:s'),
            'status' => 'confirmed',
        ]);

        // LOG::INFO('$validatedData：' . json_encode($validatedData));

        // Schedulesテーブルから予約開始時刻を特定
        $schedule_startTime = Schedule::where('stylist_id', $stylistId)
            ->where('start_time', $startTime)
            ->first();

        // サービスの利用時間より何枠を予約で埋めるかを決定
        $numbersOfSlots = $duration / 30;

        // サービス利用時間分、Schedulesテーブルを更新
        for ($i = 0; $i < $numbersOfSlots; $i++) {
            $currentStartTime = (new \DateTime($startTime))->modify("+" . ($i * 30) . " minutes")->format('Y-m-d H:i:s');

            Schedule::where('stylist_id', $stylistId)
                ->where('start_time', $currentStartTime)
                ->update(['status' => 'booked']);
        }

        try {
            Reservation::create($validatedData);

            return redirect()->route('reservation.home')->with('status', '予約が完了しました！');
            // return redirect()->action([self::class, 'home']);
        } catch (\Exception $e) {
            // エラーハンドリング（ログ記録やエラーメッセージ表示）
            Log::error('予約登録時のエラー: ' . $e->getMessage());
            return redirect()->back()->withErrors(['msg' => '予約の登録に失敗しました。']);
        }
    }
}
