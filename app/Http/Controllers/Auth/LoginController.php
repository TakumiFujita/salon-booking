<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest'); // ログインしていないユーザーのみがアクセス可能
    }

    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginUserRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        // 認証を試みる
        if (Auth::attempt($validatedData)) {
            $request->session()->regenerate();

            return redirect()->intended('reservation.home');
        }

        return back()->withErrors([
            'email' => 'ログイン情報が正しくありません。'
        ])->onlyInput('email');
    }
}
