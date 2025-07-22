<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class services extends Model
{
    use HasFactory;
    protected $table = 'services';
    protected $fillable = [
        'id',
        'device_id',
        'service_name',
        'cost',
        'service_arabic',
        'cost_arabic',
        'desc_service',
        'central',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'service_id');
    }
}
