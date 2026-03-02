<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Workspace extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'icon',
        'color',
        'plan'
    ];
    /**
     * Customizing the data check for Route Model Binding.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $workspace = $this->where($field ?? $this->getRouteKeyName(), $value)->first();

        if (!$workspace) {
            // This stops execution and returns a clean JSON error immediately
            abort(response()->json([
                'success' => false,
                'message' => "Workspace with ID ($value) not found. Please check the ID and try again.",
                'error_code' => 404,
                'data'    => null
            ], 404));
        }

        return $workspace;
    }

    /**
     * Owner of the workspace
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Organization that owns this workspace
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Projects in this workspace
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Generate slug from name
     */
    public static function generateSlug($name, $organizationId = null): string
    {
        $slug = \Illuminate\Support\Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        // Check if slug already exists (optional: filter by organization)
        $query = self::where('slug', $slug);
        if ($organizationId) {
            $query->where('organization_id', $organizationId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
            $query = self::where('slug', $slug);
            if ($organizationId) {
                $query->where('organization_id', $organizationId);
            }
        }

        return $slug;
    }
}
