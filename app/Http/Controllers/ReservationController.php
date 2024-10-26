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

        // 1週間分のフォーマットされた日付と曜日を配列に格納
        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $weekDays[] = $now->copy()->addDays($i);
        }

        // 1日分の30分刻みの時間帯を生成するための処理
        $timeSlots = [];
        $startTime = Carbon::createFromTime(9, 0); // 9:00から
        $endTime = Carbon::createFromTime(20, 0);  // 20:00まで
        while ($startTime->lessThan($endTime)) {
            // 9:00から30分刻みで時間を配列に追加
            $timeSlots[] = $startTime->format('H:i');
            $startTime->addMinutes(30);  // 30分加算
        }

        // 今週1週間分のスケジュールデータを取得
        // `start_time` が今日から1週間分のデータを取得するため、whereBetweenを使用
        $schedules = Schedule::whereBetween('start_time', [
            $now->copy()->startOfDay(), // 今日の開始時間
            $now->copy()->addDays(6)->endOfDay() // 6日後の終了時間まで
        ])->get();

        // 各日付ごとのスケジュールを格納する配列を初期化
        $weekSchedules = [];

        // 各日付（1週間分）と時間帯（9:00〜20:00の30分刻み）ごとに処理
        foreach ($weekDays as $day) {
            foreach ($timeSlots as $timeSlot) {
                // 該当の日付と時間帯のスケジュールを検索
                // データベースにあるstart_timeをフォーマットして、日付と時間が一致するか確認
                $schedule = $schedules->first(function ($s) use ($day, $timeSlot) {
                    return Carbon::parse($s->start_time)->format('Y-m-d H:i') === $day->format('Y-m-d') . ' ' . $timeSlot;
                });

                // スケジュールが存在し、statusが 'available' なら ◯、そうでなければ ✗
                $weekSchedules[$day->format('Y-m-d')][$timeSlot] = $schedule && $schedule->status === 'available' ? '◯' : '✗';
            }
        }

        $today = $now->format('Y-m-d');
        $thisWeekSchedules = Schedule::whereDate('start_time', $today)->get();

        return view('user.reservation.home', compact('services', 'currentMonth', 'weekDays', 'timeSlots', 'weekSchedules'));
    }

    public function getSchedule(Request $request)
    {
        $serviceId = $request->get('service_id');
        $service = Service::findOrFail($serviceId);
        $duration = $service->duration;

        // 今日から1週間分のスケジュールを取得
        $weekDays = [];
        $now = Carbon::now();
        for ($i = 0; $i < 7; $i++) {
            $weekDays[] = $now->copy()->addDays($i);
        }

        // 時間スロットの設定
        $timeSlots = $this->generateTimeSlots();
        $weekSchedules = $this->getWeekSchedules($weekDays, $timeSlots, $serviceId, $duration);

        // dd($weekSchedules);
        LOG::INFO($weekSchedules);

        return response()->json($weekSchedules);
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
        $schedules = Schedule::whereBetween('start_time', [
            $now->startOfDay(),
            $now->addDays(6)->endOfDay()
        ])->get();

        $weekSchedules = [];
        foreach ($weekDays as $day) {
            foreach ($timeSlots as $timeSlot) {
                $schedule = $schedules->first(function ($s) use ($day, $timeSlot) {
                    return Carbon::parse($s->start_time)->format('Y-m-d H:i') === $day->format('Y-m-d') . ' ' . $timeSlot;
                });

                // スケジュールが存在するか確認し、利用可能かどうかを判断
                if ($schedule) {
                    $weekSchedules[$day->format('Y-m-d')][$timeSlot] = $schedule->status === 'available' ? '◯' : '✗';
                } else {
                    $weekSchedules[$day->format('Y-m-d')][$timeSlot] = '◯'; // スケジュールが無い場合は空いていると見なす
                }
            }
        }

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
        $serviceId = $request->get('service_id');
        $date = $request->get('date');
        $startTime = $request->get('time');

        $service = Service::find($serviceId);

        return view('user.reservation.confirmation', compact('service', 'date', 'startTime'));
    }

    public function store(ReservationRequest $request)
    {
        $startTime = Carbon::parse($request->input('date') . ' ' . $request->input('start_time'));
        $duration = (int) $request->input('duration');
        $endTime = $startTime->copy()->addMinutes($duration);

        $validatedData = array_merge($request->validated(), [
            'stylist_id' => '1',
            'user_id' => '1',
            'start_time' => $startTime->format('Y-m-d H:i:s'),
            'end_time' => $endTime->format('Y-m-d H:i:s'),
            'status' => 'confirmed',
        ]);

        LOG::INFO('$validatedData：' . json_encode($validatedData));

        try {
            Reservation::create($validatedData);
            return redirect()->route('reservation.home')->with('status', '予約が完了しました！');
        } catch (\Exception $e) {
            // エラーハンドリング（ログ記録やエラーメッセージ表示）
            Log::error('予約登録時のエラー: ' . $e->getMessage());
            return redirect()->back()->withErrors(['msg' => '予約の登録に失敗しました。']);
        }
    }
}
