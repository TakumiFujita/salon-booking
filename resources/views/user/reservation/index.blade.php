@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h3 class="mb-4">予約一覧</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">日付</th>
                            <th scope="col">時間</th>
                            <th scope="col">サービス</th>
                            <th scope="col">スタイリスト</th>
                            <th scope="col">状態</th>
                            <th scope="col">支払い</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $reservation)
                            <tr>
                                <td>{{ $reservation->date->format('Y/m/d') }}</td>
                                <td>{{ $reservation->start_time->format('G:i') }} ~
                                    {{ $reservation->end_time->format('G:i') }}
                                </td>
                                <td>{{ $reservation->service->name }}</td>
                                <td>{{ $reservation->stylist->name }}</td>
                                <td>
                                    @if ($reservation->status === 'confirmed')
                                        <span class="badge bg-success">確定</span>
                                    @elseif($reservation->status === 'completed')
                                        <span class="badge bg-info">完了</span>
                                    @else
                                        <span class="badge bg-secondary">キャンセル</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($reservation->payment?->status === 'succeed')
                                        <span class="badge bg-success">支払い済み</span>
                                    @else
                                        <form method="POST"
                                            action="{{ route('checkout.session', ['service_id' => $reservation->service_id]) }}">
                                            @csrf
                                            <input type="hidden" name="service_id" value="{{ $reservation->service_id }}">
                                            <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                                            <button type="submit" class="btn btn-sm btn-primary">決済する</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    現在、予約はありません。
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
