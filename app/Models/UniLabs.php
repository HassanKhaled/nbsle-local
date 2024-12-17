<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniLabs extends Model
{
    use HasFactory;
    protected $table = 'uni_labs';
    protected $fillable = [
        'id',
        'name',
        'Arabicname',
        'location',
        'uni_id',
    ];

    ///////////   Relations don't work    ////////////////

    // one to many relation to UniDevices table
    public function unidevices()
    {
        return $this->hasMany(UniDevices::class,'lab_id');
    }
    // many to one relation to universitys table
    public function university()
    {
        return $this->belongsTo(universitys::class);
    }

}
