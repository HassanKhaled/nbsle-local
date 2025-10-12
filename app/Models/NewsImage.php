<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'news_id',
        'image_url'
    ];

    protected $casts = [
        'id' => 'integer',
        'news_id' => 'integer'
    ];

    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }
} 