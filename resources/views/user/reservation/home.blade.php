@extends('layouts.app')

@section('content')
    @if (session('message'))
        <div class="alert alert-info">
            {{ session('message') }}
        </div>
    @endif
    <div class="container py-5">
        <h3 class="mb-0">本日（{{ $now->isoFormat('YYYY年MM月DD日') }}）のご予約状況</h3>
        <table class="table table-borderless">
            <tbody>
                @if ($todayReservations->isNotEmpty())
                    @foreach ($todayReservations as $reservation)
                        <tr>
                            <td>
                                ・{{ $reservation->start_time->isoFormat('HH時mm分') }}〜：
                                {{ $reservation->service->name }}&nbsp;
                                @if ($reservation->payment?->status === 'succeed')
                                    <span class="badge bg-success">支払い済み</span>
                                @else
                                    <form method="POST" class="d-inline"
                                        action="{{ route('checkout.session', ['service_id' => $reservation->service_id]) }}">
                                        @csrf
                                        <input type="hidden" name="service_id" value="{{ $reservation->service_id }}">
                                        <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                                        <button type="submit" class="btn btn-sm btn-primary">決済する</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @unless ($loop->last)
                            <br>
                        @endunless
                    @endforeach
                @else
                    <p>本日の予約はありません</p>
                @endif
            </tbody>
        </table>
        <form action="{{ route('user.reservation.confirmation') }}" method="GET">
            @csrf
            <h3 class="mt-5">サービスを選択してください</h3>
            <select id="service-select" class="form-select" name="service_id">
                @foreach ($services as $service)
                    <option value="{{ $service->id }}"
                        {{ old('service_id', request('service_id')) == $service->id ? 'selected' : '' }}>
                        {{ $service->name }}（{{ $service->duration }}分）
                    </option>
                @endforeach
            </select>

            <input type="hidden" name="date" id="selectedDate">
            <input type="hidden" name="time" id="selectedTime">

            <h3 class="mt-5 mb-3">ご希望の来店日時を選択してください</h3>
            <div class="alert alert-warning p-3 mb-3">
                <p class="fw-bold mb-2">
                    ※サービス内容により、予約可能な最終時間が異なります。
                </p>
                <ul class="list-unstyled mb-0">
                    <li><span class="fw-semibold">カット＆パーマ</span>：<span class="fw-bold text-danger">17:30</span></li>
                    <li><span class="fw-semibold">カット＆カラー</span>：<span class="fw-bold text-danger">18:00</span></li>
                    <li><span class="fw-semibold">カット</span>：<span class="fw-bold text-danger">19:00</span></li>
                </ul>
            </div>

            <div id="schedule">
                @error('time')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                @if ($weekOffset > 0)
                    <a href="{{ route('user.reservation.home', ['week' => $weekOffset - 1]) }}">前へ</a>
                @endif
                @if ($weekOffset < 1)
                    <a href="{{ route('user.reservation.home', ['week' => $weekOffset + 1]) }}">次へ</a>
                @endif
                <table id="time-slots" class="table">
                    <thead>
                        <tr>
                            <th rowspan="2">{{ $currentMonth }}</th>
                        </tr>
                        <tr>
                            @foreach ($schedules['weekDays'] as $weekDay)
                                <th>{{ $weekDay->isoFormat('D(ddd)') }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schedules['timeSlots'] as $outerIndex => $timeSlot)
                            <tr>
                                <th>{{ $timeSlot }}</th>
                                @foreach ($schedules['weekDays'] as $innerIndex => $weekDay)
                                    <td>
                                        @php
                                            // weekSchedules[$weekDay->format('Y-m-d')] は日付ごとに存在し、各時間スロットの情報が格納されています
                                            $scheduleData =
                                                $schedules['weekSchedules'][$weekDay->format('Y-m-d')][$timeSlot] ??
                                                null;
                                        @endphp
                                        @if (!$scheduleData || $scheduleData['isPast'] || $scheduleData['status'] === '✗')
                                            <button class="btn" disabled
                                                style="border-color: transparent; opacity: 1;">✗</button>
                                        @else
                                            <button type="submit" class="btn"
                                                onclick="setReservation('{{ $weekDay->format('Y-m-d') }}', '{{ $timeSlot }}')">
                                                {{ $scheduleData['status'] }}
                                            </button>
                                        @endif
                                        {{-- @if ($scheduleData)
                                            @if ($scheduleData['isPast'])
                                                <button class="btn" disabled
                                                    style="border-color: transparent; opacity: 1;">✗</button>
                                            @else
                                                <button type="submit" class="btn btn-link"
                                                    onclick="setReservation('{{ $weekDay->format('Y-m-d') }}', '{{ $timeSlot }}')">
                                                    {{ $scheduleData['status'] }}
                                                </button>
                                            @endif
                                        @else
                                            <button class="btn" disabled
                                                style="border-color: transparent; opacity: 1;">✗</button>
                                        @endif --}}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </form>
    </div>
    <script>
        function setReservation(date, time) {
            // hiddenフィールドに日時をセット
            document.getElementById('selectedDate').value = date;
            document.getElementById('selectedTime').value = time;
            // フォームを送信
            document.getElementById('reservationForm').submit();
        }
    </script>
@endsection
