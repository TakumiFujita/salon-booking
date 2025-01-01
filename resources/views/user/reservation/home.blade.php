@extends('layouts.app')

@section('content')
    @if (session('message'))
        <div class="alert alert-info">
            {{ session('message') }}
        </div>
    @endif
    <div class="container py-5">
        <h3>本日（{{ $now->isoFormat('YYYY年MM月DD日') }}）のご予約状況</h3>
        @if ($todayReservations->isNotEmpty())
            @foreach ($todayReservations as $reservation)
                <tr>
                    <td>
                        ・{{ $reservation->start_time->isoFormat('HH時mm分') }}〜：
                        {{ $reservation->service->name }}&nbsp;
                        <form method="POST"
                            action="{{ route('checkout.session', ['service_id' => $reservation->service_id]) }}"
                            id="stripe-form">
                            @csrf
                            <input type="hidden" name="service_id" value={{ $reservation->service_id }}>
                            <button type="submit" id="card-button" class="btn btn-sm btn-primary">決済をする</button>
                        </form>
                    </td>
                </tr>
                @unless ($loop->last)
                    <br>
                @endunless
            @endforeach
        @else
            <p>本日の予約はありません</p>
        @endif
        <form action="{{ route('reservation.confirmation') }}" method="GET">
            @csrf
            <h3 class="mt-5">サービスを選択してください</h3>
            <select id="service-select" class="form-select" name="service_id">
                @foreach ($services as $service)
                    <option value="{{ $service->id }}"
                        {{ old('serviced_id', request('service_id')) == $service->id ? 'selected' : '' }}>
                        {{ $service->name }}（{{ $service->duration }}分）</option>
                    </option> --}}
                @endforeach
            </select>

            <input type="hidden" name="date" id="selectedDate">
            <input type="hidden" name="time" id="selectedTime">

            <h3 class="mt-5">ご希望の来店日時を選択してください</h3>
            <div id="schedule">
                @error('time')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
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
                                        @if (!$scheduleData || $scheduleData['isPast'])
                                            <button class="btn" disabled
                                                style="border-color: transparent; opacity: 1;">✗</button>
                                        @else
                                            <button type="submit" class="btn btn-link"
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
