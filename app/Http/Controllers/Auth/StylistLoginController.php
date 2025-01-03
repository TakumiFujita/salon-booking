<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StylistLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.stylist_login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::guard('stylist')->attempt($credentials)) {
            // セッションを再生成
            $request->session()->regenerate();
            return redirect()->route('stylist.home');
        }
        return back()->withErrors(['email' => 'ログイン情報が正しくありません']);
    }

    public function logout(Request $request)
    {
        Auth::guard('stylist')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('stylist.login');
    }
}
