<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    protected $fillable = [
        'stylist_id',
        'service_id',
        'user_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'price',
    ];

    protected $casts = [
        'date' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
}
