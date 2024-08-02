<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'day_of_week',
        'start_time',
        'end_time',
        'max_capacity',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class, 'tour_schedule_id');
    }
}
