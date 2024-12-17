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
    ];
    public function lab()
    {
        return $this->belongsTo(UniLabs::class);
    }


}
