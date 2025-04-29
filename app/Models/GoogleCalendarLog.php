<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleCalendarLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'google_event_id',
        'details',
    ];
}
