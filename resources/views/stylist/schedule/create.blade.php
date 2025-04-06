@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h2>スケジュール登録</h2>

        <!-- 成功メッセージの表示 -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('stylist.schedule.store') }}" method="POST">
            @csrf

            @foreach ($weekdays as $day => $label)
                <div class="mb-3">
                    <label class="form-label">{{ $label }}曜日</label>

                    <!-- 出勤しないチェックボックス -->
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input work-off-checkbox" name="{{ $day }}_off"
                            id="{{ $day }}_off" data-target="{{ $day }}-time-inputs"
                            {{ isset($existingSchedules[$day]) && $existingSchedules[$day]->status == 'unavailable' ? 'checked' : '' }}>
                        <label class="form-check-label" for="{{ $day }}_off">出勤しない</label>
                    </div>

                    <!-- 時間入力 -->
                    <div class="d-flex align-items-center time-inputs" id="{{ $day }}-time-inputs">
                        <!-- 開始時刻 -->
                        <select name="{{ $day }}_start_hour" class="form-control w-auto"
                            {{ isset($existingSchedules[$day]) && $existingSchedules[$day]->status == 'unavailable' ? 'disabled' : '' }}
                            required>
                            @for ($hour = 9; $hour <= 20; $hour++)
                                <option value="{{ sprintf('%02d', $hour) }}"
                                    {{ isset($existingSchedules[$day]) && \Carbon\Carbon::parse($existingSchedules[$day]->start_time)->format('H') == sprintf('%02d', $hour) ? 'selected' : ($hour == 9 ? 'selected' : '') }}>
                                    {{ sprintf('%02d', $hour) }}
                                </option>
                            @endfor
                        </select>
                        <span class="mx-1">:</span>
                        <select name="{{ $day }}_start_minute" class="form-control w-auto"
                            {{ isset($existingSchedules[$day]) && $existingSchedules[$day]->status == 'unavailable' ? 'disabled' : '' }}
                            required>
                            <option value="00"
                                {{ isset($existingSchedules[$day]) && \Carbon\Carbon::parse($existingSchedules[$day]->start_time)->format('i') == '00' ? 'selected' : 'selected' }}>
                                00</option>
                        </select>

                        <span class="mx-2">〜</span>

                        <!-- 終了時刻 -->
                        <select name="{{ $day }}_end_hour" class="form-control w-auto"
                            {{ isset($existingSchedules[$day]) && $existingSchedules[$day]->status == 'unavailable' ? 'disabled' : '' }}
                            required>
                            @for ($hour = 9; $hour <= 20; $hour++)
                                <option value="{{ sprintf('%02d', $hour) }}"
                                    {{ isset($existingSchedules[$day]) && \Carbon\Carbon::parse($existingSchedules[$day]->end_time)->format('H') == sprintf('%02d', $hour) ? 'selected' : ($hour == 20 ? 'selected' : '') }}>
                                    {{ sprintf('%02d', $hour) }}
                                </option>
                            @endfor
                        </select>
                        <span class="mx-1">:</span>
                        <select name="{{ $day }}_end_minute" class="form-control w-auto"
                            {{ isset($existingSchedules[$day]) && $existingSchedules[$day]->status == 'unavailable' ? 'disabled' : '' }}
                            required>
                            <option value="00"
                                {{ isset($existingSchedules[$day]) && \Carbon\Carbon::parse($existingSchedules[$day]->end_time)->format('i') == '00' ? 'selected' : 'selected' }}>
                                00</option>
                        </select>
                    </div>
                </div>
            @endforeach

            <a href="{{ route('stylist.home') }}" class="btn btn-secondary">戻る</a>
            <button type="submit" class="btn btn-primary">保存</button>
        </form>
    </div>

    <!-- JavaScriptでチェック時に時間入力を無効化 -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.work-off-checkbox');

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const targetId = checkbox.dataset.target;
                    const timeInputs = document.querySelectorAll(`#${targetId} select`);

                    timeInputs.forEach(function(input) {
                        input.disabled = checkbox.checked;
                    });
                });
            });
        });
    </script>
@endsection
