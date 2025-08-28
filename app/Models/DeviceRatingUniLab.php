<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceRatingUniLab extends Model
{
    use HasFactory;
    protected $table = "device_ratings_uni_lab";
    protected $fillable = [
        'reservation_id', 'user_id', 'device_id',
        'service_quality', 'device_info_clarity', 'search_interface',
        'request_steps_clarity', 'device_condition', 'research_results_quality',
        'device_availability', 'response_speed', 'technical_support',
        'research_success', 'recommend_service', 'feedback'
    ];

    public function reservation()
    {
        return $this->belongsTo(ReservationUniLab::class);
    }
}
