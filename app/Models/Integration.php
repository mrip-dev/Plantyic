<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Integration extends Model
{
    use HasFactory;
{
    protected $fillable = [
        'name', 'description', 'icon', 'connected', 'scopes', 'lastSync'
    ];

    protected $casts = [
        'connected' => 'boolean',
        'scopes' => 'array',
        'lastSync' => 'date',
    ];
}
