<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tour_schedule_id',
        'number_of_people',
        'status',
        'requested_date',
        'approved_date',
        'additional_info',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tourSchedule()
    {
        return $this->belongsTo(TourSchedule::class);
    }
}
