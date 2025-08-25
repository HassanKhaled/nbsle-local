<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class labs extends Model
{
    use HasFactory;
    protected $table = 'labs';
    protected $fillable = [
        'id',
        'name',
        'Arabicname',
        'dept_id',
        'uni_id',
        'fac_id',
        'pic',
        'ImagePath',
        'services',
        'accredited',
        'accredited_date',

    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'lab_id');
    }

    public function devices()
    {
        return $this->hasMany(devices::class, 'lab_id');
    }
    public function whereDept($dept){
        $mod = $this;
        $mod::where('dept_id',$dept['id'])->get();
        return $mod;
    }
    public function staff()
    {
        return $this->hasManyThrough(
        // required
            'App\Models\Staff', // the related model
            'App\Models\LabStaff', // the pivot model

            // optional
            'lab_id', // the current model id in the pivot
            'id', // the id of related model
            'id', // the id of current model
            'manager_id' // the related model id in the pivot
        );

//        return $this->belongsToMany(Staff::class, 'LabStaff',
//            'lab_id','manager_id')->withPivot('central');
    }


    public function university()
    {
        return $this->belongsTo(universitys::class, 'uni_id');
    }

    public function faculty()
    {
        return $this->belongsTo(facultys::class, 'fac_id');
    }

   
}
