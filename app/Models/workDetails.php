<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkDetails extends Model
{
    use HasFactory;
    protected $table = 'workshops_details';
    public $timestamps = false; 
    protected $fillable = [
        'id',
        'Uni_id',
        'Faculty_id',
        'workshop_ar_title',
        'workshop_en_title',
        'workshop_logoPath',
        'no_lecturers',
        'Lec_ar_names',
        'Lec_en_names',
        'Lec_ar_details',
        'Lec_en_details',
        'workshop_period',
        'st_date',
        'end_date',
        'attendees_types',
        'fees_types',     
        'fees_values', 
        'place',
        'rep_name',
        'rep_phone',
        'rep_email',
        'notes',
        'likes',
        'views',
        'is_approved'
    ];

    protected $dates = ['st_date', 'end_date'];

    // protected $casts = [
    //     'Lec_ar_names' => 'array',
    //     'Lec_en_names' => 'array',
    //     'Lec_ar_details' => 'array',
    //     'Lec_en_details' => 'array'
    // ];

    public function university()
    {
        return $this->belongsTo(universitys::class, 'Uni_id');
    }

    public function faculty()
    {
        return $this->belongsTo(facultys::class, 'Faculty_id');
    }

    // A workshop has many registrations (participants)
    public function registrations()
    {
        return $this->hasMany(WorkReg::class, 'workshop_id');
    }
    
    protected $casts = [
        'Lec_ar_names'   => 'array',
        'Lec_en_names'   => 'array',
        'Lec_ar_details' => 'array',
        'Lec_en_details' => 'array',
        'fees_types'     => 'array',
        'fees_values'    => 'array',
        'is_approved'    => 'boolean',

    ];
}

