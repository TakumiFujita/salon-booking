<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Service;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ConfirmationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $serviceId = $this->input('service_id');
            $date = $this->input('date');
            $startTime = $this->input('time');

            // サービスと所要時間を取得
            $service = Service::find($serviceId);
            $duration = $service->duration;

            // 予約の開始・終了時刻を計算
            $date = Carbon::parse($date)->format('Y-m-d');
            $startDateTime = Carbon::parse("{$date} {$startTime}");
            $endDateTime = $startDateTime->copy()->addMinutes($duration);

            // 重複する予約がないかチェック
            // ・予約テーブルのstart_timeが今回のサービス開始〜終了（-1分）時刻に被っていないこと
            // ・予約テーブルのend_timeが今回のサービス開始（+1分）〜終了時刻に被っていないこと
            $conflictingReservation = Reservation::where(function ($query) use ($startDateTime, $endDateTime) {
                $query->whereBetween('start_time', [$startDateTime, $endDateTime->subSecond()])
                    ->orWhereBetween('end_time', [$startDateTime->addSecond(), $endDateTime]);
            })->exists();

            if ($conflictingReservation) {
                $validator->errors()->add('time', '選択したサービスの終了時間が既に予約済みの時間帯と重なっています。別の時間を選択してください。');
            }
        });
    }
}
