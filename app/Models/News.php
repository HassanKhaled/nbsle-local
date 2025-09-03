<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';
    public $timestamps = false; 
    protected $fillable = [
        'id',
        'title',
        'desc',
        'img_path',
        'university_id',
    ];

    public function university()
    {
        return $this->belongsTo(universitys::class, 'university_id');
    }
}
