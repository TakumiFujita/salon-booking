<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
  protected $fillable = [
    'reservation_id',
    'stripe_payment_intent_id',
    'amount',
    'status',
  ];

  public function reservation()
  {
    return $this->belongsTo(Reservation::class);
  }
}
