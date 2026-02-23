<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Workspace extends Model
{
    use HasFactory;

    protected $fillable = [
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
}
