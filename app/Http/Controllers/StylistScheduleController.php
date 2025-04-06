<?php

namespace App\Http\Controllers;

use App\Http\Requests\Stylist\DefaultStylistScheduleRequest;
use App\Models\DefaultStylistSchedule;
use Illuminate\Support\Facades\Auth;
use App\Enums\Weekday;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StylistScheduleController extends Controller
{
    protected $weekdays;

    public function __construct()
    {
        $this->weekdays = Weekday::toArray();
    }

    public function create()
    {
        $existingSchedules = DefaultStylistSchedule::where('stylist_id', Auth::id())->get()->keyBy('weekday');

        return view('stylist.schedule.create', [
            'weekdays' => $this->weekdays,
            'existingSchedules' => $existingSchedules
        ]);
    }

    public function store(DefaultStylistScheduleRequest $request)
    {
        DB::beginTransaction();

        try {
            // スケジュールの登録処理
            foreach ($this->weekdays as $day => $label) {
                // 出勤しないチェックボックスがオンの場合、スケジュールをスキップ
                if ($request->input("{$day}_off")) {
                    continue;
                }

                // Requestクラスで変換された日時を取得
                $startTime = $request->input("{$day}_start");
                $endTime = $request->input("{$day}_end");

                // もし開始時間または終了時間が空でない場合は登録処理を行う
                if ($startTime && $endTime) {
                    // Carbonでパースする
                    $start = \Carbon\Carbon::parse($startTime);
                    $end = \Carbon\Carbon::parse($endTime);

                    // デフォルトスケジュールテーブルに登録または更新
                    DefaultStylistSchedule::updateOrCreate(
                        [
                            'stylist_id' => Auth::id(),
                            'weekday' => $day, // 曜日を保存
                        ],
                        [
                            'start_time' => $start->format('Y-m-d H:i:s'),
                            'end_time' => $end->format('Y-m-d H:i:s'),
                            'status' => 'available',
                        ]
                    );
                }
            }

            // コミット
            DB::commit();

            return redirect()->route('stylist.schedule.create')->with('success', 'スケジュールを登録しました。');
        } catch (\Exception $e) {
            // エラーが発生した場合はロールバック
            DB::rollBack();
            Log::error('Error during schedule creation: ' . $e->getMessage());
            return back()->with('error', 'スケジュール登録中にエラーが発生しました。');
        }
    }
}
