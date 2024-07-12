<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Tour extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'max_people',
        'contact_info',
        'visibility_period',
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

    // Método para calcular los cupos disponibles
    public function getAvailableSeatsAttribute()
    {
        $reservedSeats = $this->visits()->whereIn('status', ['pending', 'approved'])->sum('number_of_people');
        return $this->max_people - $reservedSeats;
    }
}
