@extends('layouts.app')

@section('content')
    @if (session('message'))
        <div class="alert alert-info">
            {{ session('message') }}
        </div>
    @endif
    <div class="container py-5">
        <h3>本日（{{ $now->isoFormat('YYYY年MM月DD日') }}）のご予約状況</h3>
        <table class="table table-borderless">
            <tbody>
                @if ($todayReservations->isNotEmpty())
                    @foreach ($todayReservations as $reservation)
                        <tr>
                            <td>
                                ・{{ $reservation->start_time->isoFormat('HH時mm分') }}〜：{{ $reservation->user->name }}様（{{ $reservation->service->name }}）&nbsp;
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
    </div>
    <form id="logout-form" action="{{ route('stylist.logout') }}" method="POST">
        @csrf
        <button type="submit">ログアウト</button>
    </form>
@endsection
