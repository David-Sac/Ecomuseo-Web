<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    // <-- Añadimos 'image_path'
    protected $fillable = [
        'title',
        'content',
        'author_id',
        'status',
        'image_path',
    ];

    public function components()
    {
        return $this->belongsToMany(Components::class, 'blog_component', 'blog_id', 'component_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
