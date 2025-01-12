@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        @yield('card-header')
                    </div>
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @yield('form')
                        {{-- <div class="mt-3 text-center">
                        <a href="{{ route('password.request') }}">Forgot your password?</a>
                    </div> --}}
                        <div class="mt-3 text-center">
                            <a href="{{ route('register') }}">新規登録はこちら</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
