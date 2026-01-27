<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopAttendance extends Model
{
    use HasFactory;

     public $timestamps = false;

    protected $fillable = [
        'workshop_id',
        'user_id',
        'day_number',
    ];

     // Attendance belongs to a workshop
     public function workshop()
    {
        return $this->belongsTo(WorkDetails::class, 'workshop_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
