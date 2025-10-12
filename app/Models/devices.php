<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class devices extends Model
{
    use HasFactory;
    protected $table = 'devices';
    protected $fillable = [
        'id',
        'name',
        'Arabicname',
        'pic',
        'ImagePath',
        'model',
        'manufacturer',
        'lab_id',
        'num_units',
        'cost',
        'services',
        'costArabic',
        'servicesArabic',
        'desc_service',
        'description',
        'ArabicDescription',
        'AdditionalInfo',
        'ArabicAddInfo',
        'ManufactureYear',
        'MaintenanceContract',
        'ManufactureCountry',
        'ManufactureWebsite',
        'entry_date',
        'state',
        'price',

    ];
    public function lab()
    {
        //edit
     
         //return $this->belongsToMany(labs::class, 'lab_id');
          return $this->belongsTo(Labs::class, 'lab_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'device_id');
    }
}
