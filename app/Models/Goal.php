<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Goal extends Model
{
    use HasFactory;
{
    protected $fillable = [
        'title', 'description', 'progress', 'target', 'category', 'dueDate', 'milestones'
    ];

    protected $casts = [
        'progress' => 'integer',
        'dueDate' => 'date',
        'milestones' => 'array',
    ];
}
