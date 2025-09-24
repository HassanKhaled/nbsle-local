<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkReg extends Model
{
    use HasFactory;
    protected $table = 'workshop_reg';
    protected $primaryKey = 'id';
    public $timestamps = false; 
    protected $fillable = [
        'id',
        'workshop_id',
        'uni_id',
        'fac_id',
        'full_name',
        'gender',
        'email',
        'par_type',
        'par_sub_type',
    ];

    // A registration belongs to a workshop
    public function workshop()
    {
        return $this->belongsTo(WorkDetails::class, 'workshop_id');
    }

    // A registration also belongs to a university
    public function university()
    {
        return $this->belongsTo(Universitys::class, 'uni_id');
    }

    // A registration also belongs to a faculty
    public function faculty()
    {
        return $this->belongsTo(fac_uni::class, 'fac_id');
    }
}

