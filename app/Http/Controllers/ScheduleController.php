<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    public function getSchedule(Request $request)
    {
        $serviceId = $request->input('service_id');
        $selectedDate = Carbon::parse($request->input('date'));
        LOG::INFO('$serviceId' . $serviceId);
        LOG::INFO('$selectedDate' . $selectedDate);

        // 選択された日付の9:00〜20:00のスケジュールを取得
        $schedules = Schedule::whereDate('start_time', $selectedDate)
            ->orderBy('start_time', 'asc')
            ->get();

        // スケジュールをJSON形式で返す
        return response()->json($schedules);
    }
}
