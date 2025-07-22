<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $table = "reservations";

    protected $fillable = [
        'id',
        'device_id',
        'user_id',
        'fac_id',
        'uni_id',
        'lab_id',
        'visitor_phone',
        'service_id',
        'samples',
        'status',
        'date',
        'time',
        'confirmation',
    ];
 
    protected $hidden = ['created_at', 'updated_at'];

/*
    public function setStatusAttribute()
    {
        $bookingDate = Carbon::parse($this->attributes['date']);
        $currentDate = Carbon::now();

        if ($bookingDate->isPast()) {
            $this->attributes['status'] = 'expire';
        } else {
            $this->attributes['status'] = 'valid';
        }
    }
    */
}
