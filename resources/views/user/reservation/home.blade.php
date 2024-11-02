@extends('layouts.app')

@section('content')
    <p class="fs-3">予約状況</p>
    <p>本日の予約はありません</p>
    <form action="{{ route('reservation.confirmation') }}" method="GET">
        @csrf
        <p class="fs-3">サービスを選択してください</p>
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

        <p class="fs-3">日付を選択してください</p>
        <div id="schedule">
            <h3>予約状況</h3>
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
