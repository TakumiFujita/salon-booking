<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
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
}
