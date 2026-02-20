<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Settings extends Model
{
    use HasFactory;
{
    protected $fillable = [
        'theme', 'primaryColor', 'fontFamily', 'displayFont', 'borderRadius', 'sidebarCompact', 'animationsEnabled', 'fontSize', 'language', 'dateFormat', 'timeFormat', 'weekStartsOn', 'emailNotifications', 'pushNotifications', 'taskReminders', 'weeklyDigest', 'teamUpdates', 'soundEnabled', 'sidebarOnRight', 'showBottomBar'
    ];

    protected $casts = [
        'sidebarCompact' => 'boolean',
        'animationsEnabled' => 'boolean',
        'emailNotifications' => 'boolean',
        'pushNotifications' => 'boolean',
        'taskReminders' => 'boolean',
        'weeklyDigest' => 'boolean',
        'teamUpdates' => 'boolean',
        'soundEnabled' => 'boolean',
        'sidebarOnRight' => 'boolean',
        'showBottomBar' => 'boolean',
    ];
}
