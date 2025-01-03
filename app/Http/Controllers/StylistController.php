<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StylistController extends Controller
{
    public function home()
    {
        return view('stylist.home'); // ログインしていないユーザーのみがアクセス可能
    }
}
