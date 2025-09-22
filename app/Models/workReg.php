<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkReg extends Model
{
    use HasFactory;
    protected $table = 'workshop_reg';
    public $timestamps = false; 
    protected $fillable = [
        'id',
        'full_name',
        'gender',
        'email',
        'par_type',
        'par_sub_type',
        'par_uni',
        'par_fac',
        'par_dept'
    ];
}

