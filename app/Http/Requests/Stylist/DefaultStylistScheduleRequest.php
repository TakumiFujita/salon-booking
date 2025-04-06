<?php

namespace App\Http\Requests\Stylist;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Enums\Weekday;

class DefaultStylistScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // DefaultStylistScheduleRequest.php

    public function prepareForValidation()
    {
        // 日時を datetime 形式に変換
        foreach (Weekday::cases() as $day) {
            $dayValue = $day->value;

            // 出勤しないチェックボックスがオンでなければ変換
            if (!$this->input("{$dayValue}_off")) {
                $startHour = $this->input("{$dayValue}_start_hour");
                $startMinute = $this->input("{$dayValue}_start_minute");
                $endHour = $this->input("{$dayValue}_end_hour");
                $endMinute = $this->input("{$dayValue}_end_minute");

                $date = now()->toDateString();  // 今日の日付

                // 開始時刻と終了時刻を datetime 形式に変換
                $this->merge([
                    "{$dayValue}_start" => "{$date} {$startHour}:{$startMinute}:00",
                    "{$dayValue}_end" => "{$date} {$endHour}:{$endMinute}:00",
                ]);
            }
        }
    }

    public function rules(): array
    {
        $rules = [];
        $date = now()->toDateString();

        foreach (Weekday::cases() as $day) {
            $dayValue = $day->value;

            // 出勤しないチェックボックスがオンならバリデーション不要
            if (!$this->input("{$dayValue}_off")) {
                // 日時形式に合わせてH:i:s形式に修正
                $rules["{$dayValue}_start"] = 'required|date_format:Y-m-d H:i:s|before:' . $date . ' ' . $dayValue . '_end';
                $rules["{$dayValue}_end"] = 'required|date_format:Y-m-d H:i:s|after:' . $date . ' ' . $dayValue . '_start';
            }
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        Log::error('Default schedule validation failed', ['errors' => $validator->errors()->all()]);
        throw new ValidationException($validator);
    }

    public function messages(): array
    {
        return [
            '*.required' => '必須項目です。',
            '*.date_format' => '時刻の形式が正しくありません（例: 09:00）。',
            '*.after' => '終了時間は開始時間より後である必要があります。',
            '*.before' => '開始時間は終了時間より前である必要があります。',
        ];
    }
}
