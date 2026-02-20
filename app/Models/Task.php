<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;
{
    protected $fillable = [
        'title', 'description', 'time', 'duration', 'priority', 'tags', 'status', 'date', 'assignee', 'project'
    ];

    protected $casts = [
        'tags' => 'array',
        'date' => 'date',
    ];
}
