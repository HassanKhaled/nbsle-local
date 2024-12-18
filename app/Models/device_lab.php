<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;


class device_lab extends Pivot
{
    use HasFactory;
    protected $table = 'device_lab';
    protected $fillable = [
        'lab_id',
        'device_id',
        'num_units',
        'cost',
        'services',
        'description',
        'ManufactureYear',
        'MaintenanceContract',
        'ManufactureCountry',
        'ManufactureWebsite',
        'entry_date',
    ];
}
