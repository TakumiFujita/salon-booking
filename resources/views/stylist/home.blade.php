@extends('layouts.app')

@section('content')
    @if (session('message'))
        <div class="alert alert-info">
            {{ session('message') }}
        </div>
    @endif
    <div class="container py-5">
        <h3>本日のご予約状況</h3>
    </div>
    <form id="logout-form" action="{{ route('stylist.logout') }}" method="POST">
        @csrf
        <button type="submit">ログアウト</button>
    </form>
@endsection
