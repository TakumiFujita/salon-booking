@extends('layouts.app')

@section('title', 'メールアドレスの確認')

@section('content')
    <div class="container mt-5">
        <div class="text-center">
            <h1 class="display-5 fw-bold mb-4">メールアドレスの確認が必要です</h1>
            <p class="lead mb-4">登録時に送信されたメールに記載されたリンクをクリックしてください。</p>
        </div>
        <div class="d-flex justify-content-center">
            <form method="POST" action="{{ route('verification.send') }}" class="text-center">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg px-5 py-2">
                    確認メールを再送信する
                </button>
            </form>
        </div>
        @if (session('message'))
            <div class="alert alert-success mt-4 text-center" role="alert">
                {{ session('message') }}
            </div>
        @endif
    </div>
@endsection
