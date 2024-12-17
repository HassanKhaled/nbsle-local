<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;
    protected $table = '_managers';
    protected $fillable = [
        'Id',
        'name',
        'telephone',
        'email',
        'staff',

    ];

    public function lab()
    {
        return $this->hasManyThrough(
        // required
            'App\Models\labs', // the related model
            'App\Models\LabStaff', // the pivot model

            // optional
            'manager_id', // the current model id in the pivot
            'id', // the id of related model
            'id', // the id of current model
            'lab_id' // the related model id in the pivot
        );
    }
}
