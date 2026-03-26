<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'user_id',
        'name',
        'email',
        'role',
        'department',
        'status',
        'tasks_assigned',
        'tasks_completed',
        'invitation_status',
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
                'message' => "Team member with ID ($value) not found. Please check the ID and try again.",
                'error_code' => 404,
                'data'    => null
            ], 404));
        }

        return $workspace;
    }
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
