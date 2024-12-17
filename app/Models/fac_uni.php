<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\App;

class fac_uni extends Model
{
    use HasFactory;
    protected $table = 'fac_uni';
    protected $fillable = [
        'id',
        'name',
        'Arabicname',
        'pic',
        'website',
        'uni_id',
        'fac_id',
        'ImagePath',
        'coor_id'
    ];
//
//    function university(){
//        return $this->belongsTo(universitys::class);
//    }
//    function faculty(){
//        return $this->belongsTo(facultys::class);
//    }
//    function user(){
//        return $this->belongsTo(User::class);
//    }
}
