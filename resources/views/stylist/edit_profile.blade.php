@extends('layouts.app')

@section('title', 'スタイリストプロフィール編集')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>スタイリストプロフィール編集</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('stylist.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">名前</label>
                                <input type="text" id="name" name="name" class="form-control"
                                    value="{{ Auth::guard('stylist')->user()->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">メールアドレス</label>
                                <input type="email" id="email" name="email" class="form-control"
                                    value="{{ Auth::guard('stylist')->user()->email }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">新しいパスワード</label>
                                <input type="password" id="password" name="password" class="form-control">
                                <small class="text-muted">パスワードを変更したい場合のみ入力してください。</small>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">パスワード確認</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="introduction" class="form-label">自己紹介</label>
                                <textarea id="introduction" name="introduction" class="form-control" rows="3" required>{{ Auth::guard('stylist')->user()->introduction }}</textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">更新する</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
