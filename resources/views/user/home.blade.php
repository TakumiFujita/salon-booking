@extends('layouts.app')

@section('content')
    <p class="fs-3">予約状況</p>
    <p>本日の予約はありません</p>
    <p class="fs-3">サービスを選択してください</p>
    <select id="service-select" class="form-select">
        @foreach ($services as $service)
            <option value="{{ $service->id }}">{{ $service->name }}</option>
        @endforeach
    </select>
    <p class="fs-3">日付を選択してください</p>
    {{-- <div class="input-group date mb-3" id="datetimepicker1">
        <input type="text" class="form-control" id="datepicker" placeholder="yyyy-mm-dd">
        <div class="input-group-text" data-toggle="datetimepicker">
            <i class="far fa-calendar-alt"></i>
        </div>
    </div> --}}
    {{-- <button id="check-schedule" type="button" class="btn btn-primary">予約状況を確認する</button> --}}
    <div id="schedule">
        <h3>予約状況</h3>
        <table id="time-slots" class="table">
            <thead>
                <tr>
                    <th rowspan="2">{{ $currentMonth }}</th>
                </tr>
                <tr>
                    @foreach ($weekDays as $weekDay)
                        <th>{{ $weekDay->isoFormat('D(ddd)') }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                {{-- @foreach ($timeSlots as $timeSlot)
                    <tr>
                        <th>{{ $timeSlot }}</th>
                        @foreach ($weekDays as $weekDay)
                            <td>
                                {{ $weekSchedules[$weekDay->format('Y-m-d')][$timeSlot] }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach --}}
            </tbody>
        </table>
    </div>
    <script>
        function fetchSchedule(serviceId) {
            fetch(`/get-schedule?service_id=${serviceId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('ネットワークエラーが発生しました。');
                    }
                    return response.json();
                })
                .then(data => {
                    const timeSlotsTableBody = document.querySelector('#time-slots tbody');
                    timeSlotsTableBody.innerHTML = '';

                    const weekDays = Object.keys(data); // dataのキー（日付）を取得
                    for (const time of Object.keys(data[weekDays[0]])) {
                        const row = document.createElement('tr');

                        // 時間帯セルを追加
                        const timeCell = document.createElement('th');
                        timeCell.textContent = time;
                        row.appendChild(timeCell);

                        // 各曜日ごとのステータスを追加
                        for (const weekDay of weekDays) {
                            const statusCell = document.createElement('td');
                            const status = data[weekDay][time] || ''; // ステータスを取得（存在しない場合は空）
                            statusCell.textContent = status;
                            row.appendChild(statusCell);
                        }

                        timeSlotsTableBody.appendChild(row); // 行をテーブルに追加
                    }
                })
                .catch(error => {
                    console.error('エラー:', error);
                });
        }

        document.getElementById('service-select').addEventListener('change', function() {
            const serviceId = this.value;
            fetchSchedule(serviceId);
        });

        // 初期表示時に自動的にカットの予約状況を表示
        document.addEventListener('DOMContentLoaded', function() {
            const initialServiceId = document.getElementById('service-select').value;
            fetchSchedule(initialServiceId);
        });
    </script>
@endsection
