<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dept_fac extends Model
{
    use HasFactory;
    protected $table = 'dept_fac';
    protected $fillable = [
       // 'name',
        'dept_id',
        'uni_id',
        'fac_id',
        'coor_id',
    ];

    function department(){
        return $this->belongsTo(departments::class);
    }
    function university(){
        return $this->belongsToMany(universitys::class);
    }
    function faculty(){
        return $this->belongsToMany(facultys::class);
    }
    function user(){
        return $this->belongsTo(User::class);
    }
}
