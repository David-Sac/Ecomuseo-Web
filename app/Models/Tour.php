<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    // <-- Añadimos 'image_path'
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'max_people',
        'contact_info',
        'visibility_period',
        'image_path',
    ];

    public function components()
    {
        return $this->belongsToMany(Components::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function volunteers()
    {
        return $this->belongsToMany(User::class, 'tour_volunteer', 'tour_id', 'volunteer_id');
    }

    public function schedules()
    {
        return $this->hasMany(TourSchedule::class);
    }
}
