<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'dni',
        'phone',
        'birthdate',
        'email',
        'password',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'author_id');
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_volunteer', 'volunteer_id', 'task_id')
                    ->withPivot('assigned_date', 'completed_date', 'status')
                    ->withTimestamps();
    }

    public function tours()
    {
        return $this->belongsToMany(Tour::class, 'tour_volunteer', 'volunteer_id', 'tour_id');
    }
}
