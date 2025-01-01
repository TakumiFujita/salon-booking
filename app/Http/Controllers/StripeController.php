<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function checkout(Request $request)
    {
        $service_id = $request->input('service_id');
        $stripe_price_id = config('services.stripe.prices.' . $service_id);

        session()->flash('stripe_success_message', '決済が成功しました！');
        session()->flash('stripe_cancel_message', '決済がキャンセルされました。');

        return $request->user()->checkout([$stripe_price_id => 1], [
            'success_url' => route('reservation.redirect', ['status' => 'success']),
            'cancel_url' => route('reservation.redirect', ['status' => 'cancel']),
        ]);
    }
}
