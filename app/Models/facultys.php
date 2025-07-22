<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class facultys extends Model
{
    use HasFactory;
    protected $table = 'facultys';
    protected $fillable = [
        'id',
        'name',
        'Arabicname',
    ];

    function user(){
        return $this->hasOne(User::class);
    }
    function university (){
        return $this->belongsToMany(universitys::class, 'fac_uni', 'uni_id','id');
    }
    function department (){
        return $this->belongsToMany(departments::class,dept_fac::class);
    }

    
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'fac_id');
    }
}
