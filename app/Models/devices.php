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
        'views',

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

    public function ratings()
{
    return $this->hasManyThrough(
        \App\Models\DeviceRating::class, // final model
        \App\Models\Reservation::class,  // intermediate model
        'device_id',   // Foreign key on reservations table
        'reservation_id', // Foreign key on device_ratings table
        'id',           // Local key on devices table
        'id'            // Local key on reservations table
    );
}
}
