<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;
{
    protected $fillable = [
        'type', 'title', 'message', 'read', 'createdAt', 'link', 'actor'
    ];

    protected $casts = [
        'read' => 'boolean',
        'createdAt' => 'date',
        'actor' => 'array',
    ];
}
