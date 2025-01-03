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
        $this->middleware('guest')->except('logout'); // ログインしていないユーザーのみがアクセス可能
    }

    public function showLoginForm(): View
    {
        return view('auth.user_login');
    }

    public function login(LoginUserRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        // 認証を試みる
        if (Auth::guard('web')->attempt($validatedData)) {
            $request->session()->regenerate();
            return redirect()->intended(route('user.reservation.home'));
        }
        return back()->withErrors([
            'email' => 'ログイン情報が正しくありません。'
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // 1. ユーザーをログアウトさせる
        Auth::guard('web')->logout();

        // 2. セッションを無効にする
        $request->session()->invalidate();

        // 3. 新しいCSRFトークンを再生成する
        $request->session()->regenerateToken();

        // 4. トップページにリダイレクトする
        return redirect()->route('login');
    }
}
