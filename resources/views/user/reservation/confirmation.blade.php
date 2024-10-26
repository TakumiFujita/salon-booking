@extends('layouts.app')

@section('content')
    <p class="fs-3">予約確認</p>
    <h5>メニュー</h5>
    <p>{{ $service->name }}</p>
    <h5>予約日</h5>
    <p>{{ \Carbon\Carbon::parse($date)->format('Y年m月d日') }}</p>
    <h5>開始時間</h5>
    <p>{{ \Carbon\Carbon::parse($startTime)->format('G:i') }}</p>
    <h5>所要時間</h5>
    <p>{{ $service->duration }}分</p>
    <h5>料金</h5>
    <p>{{ $service->price }}円</p>
    <a href="{{ route('reservation.home') }}" class="btn btn-primary">戻る</a>
    <button type="button" class="btn btn-primary">予約する</button>
@endsection
