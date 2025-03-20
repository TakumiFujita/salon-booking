<?php

namespace App\Http\Controllers;

use App\Http\Requests\Stylist\ProfileUpdateRequest;
use App\Models\Reservation;
use App\Models\Stylist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class StylistController extends Controller
{
    public function home()
    {
        $now = Carbon::now();
        $todayReservations = Reservation::where('stylist_id', Auth::guard('stylist')->id())->whereDate('start_time', $now->format('Y-m-d'))->get();

        return view('stylist.home', compact('now', 'todayReservations'));
    }

    public function edit()
    {
        $stylist = Auth::user(); // ログインしているスタイリストを取得
        return view('stylist.edit_profile', compact('stylist'));
    }

    public function update(ProfileUpdateRequest $request)
    {
        LOG::INFO("1");
        $stylist = Auth::user();

        $data = $request->only('name', 'email');
        LOG::INFO("2");

        // パスワードが入力されている場合のみ、パスワードを更新
        if ($request->filled('password')) {
            LOG::INFO("3");
            $data['password'] = bcrypt($request->password); // パスワードをハッシュ化して保存
        }

        LOG::INFO("4");
        LOG::INFO($data);

        if ($stylist instanceof Stylist) {
            // ユーザーは User モデルのインスタンスです
            LOG::INFO("5");
            $stylist->update($data);
            LOG::INFO("6");
        }

        LOG::INFO("7");

        return redirect()->route('stylist.profile.edit')->with('success', 'プロフィールを更新しました。');
    }
}
