<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmationRequest;
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

        $schedules = $this->getSchedule();
        // LOG::INFO('$schedules' . json_encode($schedules));
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

        // 時間スロットの設定
        $timeSlots = $this->generateTimeSlots();
        $weekSchedules = $this->getWeekSchedules($weekDays, $timeSlots, $serviceId, $duration);

        return [
            'weekSchedules' => $weekSchedules,
            'timeSlots' => $timeSlots,
            'weekDays' => $weekDays,
        ];
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

        $start = $now->copy()->setTime(9, 0); // 今日の09:00
        $end = $now->copy()->addDays(6)->setTime(19, 30); // 6日後の19:30

        $schedules = Schedule::whereBetween('start_time', [$start, $end])->get();

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
                    $weekSchedules[$day->format('Y-m-d')][$timeSlot] = 'unavailable';
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

    public function confirmation(ConfirmationRequest $request)
    {
        $serviceId = $request->input('service_id');
        $date = $request->input('date');
        $startTime = $request->input('time');

        $service = Service::find($serviceId);

        return view('user.reservation.confirmation', compact('service', 'date', 'startTime'));
    }

    public function store(ReservationRequest $request)
    {
        // LOG::INFO('$request' . $request);
        $stylistId = '1';
        $date = Carbon::parse($request->input('date'))->format('Y-m-d');
        $startTime = Carbon::parse($date . ' ' . $request->input('start_time'));
        // LOG::INFO('$startTime' . $startTime);
        $duration = (int) $request->input('duration');
        $endTime = $startTime->copy()->addMinutes($duration);
        // LOG::INFO('$duration' . $duration);

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
        } catch (\Exception $e) {
            // エラーハンドリング（ログ記録やエラーメッセージ表示）
            Log::error('予約登録時のエラー: ' . $e->getMessage());
            return redirect()->back()->withErrors(['msg' => '予約の登録に失敗しました。']);
        }
    }
}
