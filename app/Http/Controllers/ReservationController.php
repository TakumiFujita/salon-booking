<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmationRequest;
use Carbon\Carbon;
use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\User;
use App\Mail\ConfirmReservationSalon2User;
use App\Mail\ReservationNotificationSalon2Stylist;
use App\Models\Stylist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function home(Request $request)
    {
        $services = Service::all();
        $now = Carbon::now();
        $currentMonth = $now->isoFormat('YYYY年MM月');

        $weekOffset = (int)$request->query('week', 0); // 0=今週、1=翌週

        // 今週もしくは翌週（日曜から土曜まで）を取得
        $startOfWeek = $now->copy()->addWeeks($weekOffset)->startOfWeek();
        $endOfWeek = $now->copy()->addWeeks($weekOffset)->endOfWeek();

        $schedules = $this->getSchedule($startOfWeek);

        // 本日の予約を取得
        $todayStart = Carbon::today()->startOfDay(); // 今日の0時0分0秒
        $todayEnd = Carbon::today()->endOfDay();     // 今日の23時59分59秒
        $todayReservations = Reservation::whereBetween('start_time', [$todayStart, $todayEnd])
            ->orderBy('start_time')
            ->get();

        return view('user.reservation.home', compact('services', 'currentMonth', 'schedules', 'weekOffset', 'now', 'todayReservations'));
    }

    public function redirect(Request $request)
    {
        if ($request->query('status') === 'success') {
            session()->flash('message', '決済が成功しました！');
        } elseif ($request->query('status') === 'cancel') {
            session()->flash('message', '決済がキャンセルされました。');
        }

        return redirect()->route('user.reservation.home');
    }

    public function getSchedule(Carbon $startOfWeek, int $serviceId = 1)
    {
        // $serviceId = $request->get('service_id');
        $service = Service::findOrFail($serviceId);
        $duration = $service->duration;

        // 基準日から1週間分のスケジュールを取得
        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $weekDays[] = $startOfWeek->copy()->addDays($i);
        }

        // 時間スロットの設定
        $timeSlots = $this->generateTimeSlots();
        $result = $this->getWeekSchedules($weekDays, $startOfWeek, $timeSlots, $serviceId, $duration);
        $weekSchedules = $result;

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

    private function getWeekSchedules($weekDays, $startOfWeek, $timeSlots, $serviceId, $duration = null)
    {
        $now = Carbon::now();
        $endOfWeek = $startOfWeek->copy()->addDays(6)->setTime(19, 30); // 6日後の19:30

        $schedules = Schedule::whereBetween('start_time', [$startOfWeek, $endOfWeek])->get();

        $weekSchedules = [];
        $isPasts = [];
        foreach ($weekDays as $day) {
            foreach ($timeSlots as $timeSlot) {
                $schedule = $schedules->first(function ($s) use ($day, $timeSlot) {
                    return Carbon::parse($s->start_time)->format('Y-m-d H:i') === $day->format('Y-m-d') . ' ' . $timeSlot;
                });

                // 現在時刻より前かどうかを判断
                $isPast = false;
                if ($schedule) {
                    $isPast = Carbon::parse($schedule->start_time)->lessThan($now);
                }
                // スケジュールの状態と過ぎているかどうかの情報をweekSchedulesに格納
                $weekSchedules[$day->format('Y-m-d')][$timeSlot] = [
                    'status' => $schedule ? ($schedule->status === 'available' ? '◯' : '✗') : 'unavailable',
                    'isPast' => $isPast,
                ];
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
        $validated = $request->validated();
        $stylistId = '1';
        $date = Carbon::parse($validated['date'])->format('Y-m-d');
        $startTime = Carbon::parse($date . ' ' . $validated['start_time']);
        $duration = (int) $validated['duration'];
        $endTime = $startTime->copy()->addMinutes($duration);

        $validatedData = array_merge($validated, [
            'stylist_id' => $stylistId,
            'user_id' => Auth::id(),
            'start_time' => $startTime->format('Y-m-d H:i:s'),
            'end_time' => $endTime->format('Y-m-d H:i:s'),
            'status' => 'confirmed',
        ]);

        $reservedServiceName = Service::where('id', $validatedData['service_id'])->pluck('name')->first();

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
            $user = User::where('id', $validatedData['user_id'])->first();
            $stylist = Stylist::where('id', $validatedData['stylist_id'])->first();

            // 利用者への予約完了メール
            Mail::to($user->email)->send(new ConfirmReservationSalon2User((object)$validatedData, $reservedServiceName, $user->name));
            // スタイリストへの新規予約通知メール
            Mail::to($stylist->email)->send(new ReservationNotificationSalon2Stylist((object)$validatedData, $reservedServiceName, $user->name, $stylist->name));

            return redirect()->route('user.reservation.home')->with('status', '予約が完了しました！確認メールを送信しておりますので、もし届いていない場合はお手数ですが直接お店へご連絡ください');
        } catch (\Exception $e) {
            // エラーハンドリング（ログ記録やエラーメッセージ表示）
            return redirect()->back()->withErrors(['msg' => '予約の登録に失敗しました。']);
        }
    }
}
