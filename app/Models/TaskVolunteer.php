<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TaskVolunteer extends Pivot
{
    protected $table = 'task_volunteer';
    protected $fillable = [
        'task_id', 'volunteer_id',
        'assigned_date', 'completed_date', 'status'
    ];
}