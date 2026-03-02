<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'onboarding_questions',
        'onboarding_answers',
    ];

    protected $casts = [
        'onboarding_questions' => 'json',
        'onboarding_answers' => 'json',
    ];

    /**
     * Owner of the organization
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Workspaces in this organization
     */
    public function workspaces()
    {
        return $this->hasMany(Workspace::class);
    }

    /**
     * Generate slug from name
     */
    public static function generateSlug($name): string
    {
        $slug = \Illuminate\Support\Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        // Check if slug already exists
        while (self::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
