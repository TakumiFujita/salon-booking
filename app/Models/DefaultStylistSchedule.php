<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultStylistSchedule extends Model
{
    use HasFactory;

    protected $table = 'default_stylist_schedules';

    protected $fillable = ['stylist_id', 'weekday', 'start_time', 'end_time', 'status']; // 必要なカラムをfillableに追加

    public function stylist()
    {
        return $this->belongsTo(Stylist::class);
    }
}
