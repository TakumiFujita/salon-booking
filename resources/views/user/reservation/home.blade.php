@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h3>本日（{{ $now->isoFormat('YYYY年MM月DD日') }}）のご予約状況</h3>
        @if ($todayReservations->isNotEmpty())
            @foreach ($todayReservations as $reservation)
                <tr>
                    <td>・{{ $reservation->start_time->isoFormat('HH時mm分') }}〜：{{ $reservation->service->name }}
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
                                        @if ($schedules['weekSchedules'][$weekDay->format('Y-m-d')][$timeSlot] === '◯')
                                            <button type="submit" class="btn btn-link"
                                                onclick="setReservation('{{ $weekDay }}', '{{ $timeSlot }}')">◯</button>
                                        @elseif ($schedules['weekSchedules'][$weekDay->format('Y-m-d')][$timeSlot] === '✗')
                                            <button class="btn" disabled
                                                style="border-color: transparent; opacity: 1;">✗</button>
                                        @endif
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
