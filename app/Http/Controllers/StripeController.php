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

        // URLを作成
        $successUrl = route('user.reservation.redirect', [
            'status' => 'success',
            'reservation_id' => $request->reservation_id,
        ]);
        $cancelUrl = route('user.reservation.redirect', ['status' => 'cancel']);

        // {CHECKOUT_SESSION_ID} を文字列として追加
        $successUrl .= '&session_id={CHECKOUT_SESSION_ID}';

        // Stripe Checkout にリダイレクト
        return $request->user()->checkout(
            [$stripe_price_id => 1],
            [
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
            ]
        );
    }
}
