<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = "reservations";

    protected $fillable = [
        'id',
        'device_id',
        'visitor_name',
        'visitor_phone',
        'status',
        'date',
        'time',
    ];
    
/*
    protected $fillable = [
        'id',
        'name',
        'description',
    ];
    */
    protected $hidden = ['created_at', 'updated_at'];
}
