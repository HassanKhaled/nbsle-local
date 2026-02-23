<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateRequest  extends Model
{
    use HasFactory;
  protected $fillable = [
        'name',
        'email',
        'workshop_id',
        'user_id',
        'days',
        'cert_count',
        'cost',
        'status',
        'image_receipt',
        'reason_rejection',
    ];

    protected $casts = [
        'days' => 'array',
    ];
    public function workshop()
    {
        return $this->belongsTo(WorkDetails::class, 'workshop_id');
    }
}
