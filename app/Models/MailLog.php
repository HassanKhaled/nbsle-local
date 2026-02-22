<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailLog extends Model
{
    use HasFactory;

        protected $fillable = [
            'workshop_id', /// indexed in DB 
            'from_email',
            'to_email',
            'subject',
            'status',   /// Enum : success or failed 
            'error_message'
            ];
    }
