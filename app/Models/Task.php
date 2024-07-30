<?php

// App\Models\Task.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'type','components'];

    // Asegurarte de que components sea un array cuando se acceda
    protected $casts = [
        'components' => 'array',
    ];


    // Accesor para obtener los componentes como una colección
    public function getComponentListAttribute()
    {
        return collect($this->components);
    }

    public function volunteers()
    {
        return $this->belongsToMany(User::class, 'task_volunteer', 'task_id', 'volunteer_id')
                    ->withPivot('assigned_date', 'completed_date', 'status')
                    ->withTimestamps();
    }
}
