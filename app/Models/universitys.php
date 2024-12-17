<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class universitys extends Model
{
    use HasFactory;
//    protected $table = 'Universitys';
    protected $table = 'universitys';
    protected $fillable = [
        'id',
        'name',
        'Arabicname',
        'pic',
        'type',
        'website',
        'ImagePath',
        'coordinator_id'
    ];

    function user(){
        return $this->hasMany(User::class);
    }
    function facultys(){
        return $this->belongsToMany(facultys::class, 'fac_uni', 'fac_id','id');
    }
    public function labs()
    {
        return $this->hasMany(UniLabs::class,'uni_id');
    }

}

