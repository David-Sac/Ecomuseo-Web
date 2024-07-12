<?php

// App\Models\Task.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'type'];

    public function volunteers()
    {
        return $this->belongsToMany(User::class, 'task_volunteer', 'task_id', 'volunteer_id')
                    ->withPivot('assigned_date', 'completed_date', 'status')
                    ->withTimestamps();
    }
}
