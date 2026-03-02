<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'workspace_id',
        'name',
        'slug',
        'description',
        'tasks',
        'completed',
        'members',
        'status',
        'dueDate',
        'createdAt'
    ];

    protected $casts = [
        'members' => 'array',
        'dueDate' => 'date',
        'createdAt' => 'date',
    ];

    /**
     * Workspace that owns this project
     */
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    /**
     * Generate slug from name
     */
    public static function generateSlug($name, $workspaceId = null): string
    {
        $slug = \Illuminate\Support\Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        // Check if slug already exists (optional: filter by workspace)
        $query = self::where('slug', $slug);
        if ($workspaceId) {
            $query->where('workspace_id', $workspaceId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
            $query = self::where('slug', $slug);
            if ($workspaceId) {
                $query->where('workspace_id', $workspaceId);
            }
        }

        return $slug;
    }
