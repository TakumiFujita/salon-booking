<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\ProfileUpdateRequest;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('auth.edit_profile', compact('user'));
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();

        // フォームから送信された情報（name, email, password）を取得
        $data = $request->only('name', 'email');

        // パスワードが入力されている場合のみ、パスワードを更新
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password); // パスワードをハッシュ化して保存
        }

        // ユーザー情報を一度のupdateで保存
        if ($user instanceof User) {
            // ユーザーは User モデルのインスタンスです
            $user->update($data);
        }

        return redirect()->route('user.profile.edit')->with('success', 'プロフィールを更新しました。');
    }
}
