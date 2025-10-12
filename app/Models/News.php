<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';
    protected $fillable = [
        'id',
        'title',
        'desc',
        'img_path',
        'university_id',
        'publish_date',
        'time',
        'location',
        'is_active'
        
    ];
    protected $casts = [
        'publish_date' => 'date',
        'is_active' => 'boolean',
    ];
    public function university()
    {
        return $this->belongsTo(universitys::class, 'university_id');
    }
     public function newsImages(): HasMany
    {
        return $this->hasMany(NewsImage::class);
    }
}
