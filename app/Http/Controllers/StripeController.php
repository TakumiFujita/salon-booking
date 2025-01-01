<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function checkout(Request $request)
    {
        $service_id = $request->input('service_id');
        $stripe_price_id = config('services.stripe.prices.' . $service_id);
        return $request->user()->checkout([$stripe_price_id => 1], [
            'success_url' => route('reservation.home'),
            'cancel_url' => route('reservation.home'),
        ]);
    }
}
