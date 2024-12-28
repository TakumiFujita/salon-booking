<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // 新規登録フォームを表示
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(RegisterUserRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // ユーザー登録イベントを発行
        event(new Registered($user));

        // ユーザーをログイン
        Auth::login($user);

        return redirect()->route('reservation.home');
    }
}
