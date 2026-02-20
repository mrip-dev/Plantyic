<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model
{
    use HasFactory;
{
    protected $fillable = [
        'title', 'content', 'tags', 'pinned', 'createdAt', 'updatedAt', 'folder'
    ];

    protected $casts = [
        'tags' => 'array',
        'pinned' => 'boolean',
        'createdAt' => 'date',
        'updatedAt' => 'date',
    ];
}
