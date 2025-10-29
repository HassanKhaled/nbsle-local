<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniDevices extends Model
{
    use HasFactory;
    protected $table = 'uni_devices';
    protected $fillable = [
        'id',
        'name',
        'Arabicname',
        'pic',
        'ImagePath',
        'model',
        'manufacturer',
        'lab_id',
        'uni_id',
        'num_units',
        'cost',
        'services',
        'costArabic',
        'servicesArabic',
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
       // return $this->belongsToMany(UniLabs::class, 'uni_device_lab', 'device_id', 'lab_id');
         return $this->belongsTo(UniLabs::class);
    }

    public function reservations()
    {
        return $this->hasMany(ReservationUniLab::class, 'device_id', 'id');
    }
     public function ratings()
    {
        return $this->hasManyThrough(
            \App\Models\DeviceRatingUniLab::class, // final model
            \App\Models\ReservationUniLab::class,  // intermediate model
            'device_id',   // Foreign key on reservations table
            'reservation_id', // Foreign key on device_ratings table
            'id',           // Local key on devices table
            'id'            // Local key on reservations table
        );
    }

}
