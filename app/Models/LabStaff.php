<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabStaff extends Model
{
    use HasFactory;
    protected $table = '_lab_managers';
    protected $fillable = [
        'Id',
        'lab_id',
        'manager_id',
        'central',
    ];
}
