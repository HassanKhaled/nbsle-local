<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class departments extends Model
{
    use HasFactory;
    protected $table = 'departments';
    protected $fillable = [
        'id',
        'name',
        'Arabicname',
    ];

    function user(){
        return $this->hasOne(User::class);
    }
    function faculty (){
        return $this->belongsToMany(facultys::class,dept_fac::class);
    }
}
