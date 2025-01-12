<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    // メール確認通知を表示するページ
    public function showNotice()
    {
        return view('auth.verify');
    }

    // 確認リンクをクリックした際の処理
    public function verify(EmailVerificationRequest $request)
    {
        // メール確認を完了（email_verified_atにタイムスタンプを設定）
        $request->fulfill();

        // ユーザーをログイン
        Auth::login($request->user());

        return redirect()->route('user.reservation.home');
    }

    // 確認メールを再送信する処理
    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '確認メールを再送信しました');
    }
}
