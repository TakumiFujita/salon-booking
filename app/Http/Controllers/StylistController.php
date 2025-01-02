<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StylistController extends Controller
{
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

    public function logout()
    {
        Auth::guard('stylist')->logout();
        return redirect()->route('stylist.login');
    }

    public function home()
    {
        return view('stylist.home');
    }
}
