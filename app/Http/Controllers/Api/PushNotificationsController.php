<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Models\UserPreference;
use App\Services\FCMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PushNotificationsController extends Controller
{
    protected FCMService $fcm;

    public function __construct(FCMService $fcm)
    {
        $this->fcm = $fcm;
    }

    /**
     * Save FCM device token
     */
    public function saveToken(Request $request)
    {
        $request->validate([
            'device_token' => 'required|string|unique:device_tokens,device_token',
        ]);

        DeviceToken::updateOrCreate(
            ['device_token' => $request->device_token],
            ['user_id' => auth()->id() ?? null]
        );

        return response()->json([
            'message' => 'Device token saved successfully'
        ]);
    }

    /**
     * Send push notification to a single device
     */
    public function sendNotification(Request $request)
    {
        $request->validate([
            'device_token' => 'required|string',
            'title' => 'required|string',
            'body' => 'required|string',
            'data' => 'nullable|array'
        ]);

        try {
            $response = $this->fcm->sendNotification(
                $request->device_token,
                $request->title,
                $request->body,
                $request->data ?? []
            );

            return response()->json([
                'success' => true,
                'response' => $response
            ]);

        } catch (\Throwable $e) {
            Log::error('FCM Send Error: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send push notification to all users
     */
    public function sendToAll(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'data' => 'nullable|array'
        ]);

        $tokens = DeviceToken::pluck('device_token')->toArray();

        if (empty($tokens)) {
            return response()->json([
                'success' => false,
                'message' => 'No device tokens found'
            ], 404);
        }

        $failed = [];
        foreach ($tokens as $token) {
            try {
                $this->fcm->sendNotification(
                    $token,
                    $request->title,
                    $request->body,
                    $request->data ?? []
                );
            } catch (\Throwable $e) {
                Log::error("Failed to send FCM to {$token}: ".$e->getMessage());
                $failed[] = $token;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Notifications sent',
            'failed_tokens' => $failed
        ]);
    }

    /**
     * Send notifications based on user preferences
     * Matches preferences with the provided criteria
     */
    public function sendByPreferences(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'data' => 'nullable|array',
            'criteria' => 'nullable|array',
            'criteria.price' => 'nullable|numeric',
            'criteria.vehicle_type' => 'nullable|string',
            'criteria.make' => 'nullable|string',
            'criteria.model' => 'nullable|string',
            'criteria.year' => 'nullable|string',
            'criteria.location' => 'nullable|string',
        ]);

        // Get active preferences
        $query = UserPreference::where('is_active', true);

        // Apply criteria filters
        $criteria = $request->input('criteria', []);
        
        if (!empty($criteria['price'])) {
            $query->where(function($q) use ($criteria) {
                $q->whereNull('price')
                  ->orWhere('price', '>=', $criteria['price']);
            });
        }

        if (!empty($criteria['vehicle_type'])) {
            $query->where(function($q) use ($criteria) {
                $q->whereNull('vehicle_type')
                  ->orWhere('vehicle_type', $criteria['vehicle_type']);
            });
        }

        if (!empty($criteria['make'])) {
            $query->where(function($q) use ($criteria) {
                $q->whereNull('make')
                  ->orWhere('make', $criteria['make']);
            });
        }

        if (!empty($criteria['model'])) {
            $query->where(function($q) use ($criteria) {
                $q->whereNull('model')
                  ->orWhere('model', $criteria['model']);
            });
        }

        if (!empty($criteria['year'])) {
            $query->where(function($q) use ($criteria) {
                $q->whereNull('year')
                  ->orWhere('year', $criteria['year']);
            });
        }

        if (!empty($criteria['location'])) {
            $query->where(function($q) use ($criteria) {
                $q->whereNull('location')
                  ->orWhere('location', $criteria['location']);
            });
        }

        $preferences = $query->get();

        if ($preferences->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No matching preferences found'
            ], 404);
        }

        // Get unique user IDs from preferences
        $userIds = $preferences->pluck('user_id')->filter()->unique();

        // Get device tokens for these users
        $tokens = DeviceToken::whereIn('user_id', $userIds)
            ->pluck('device_token')
            ->toArray();

        if (empty($tokens)) {
            return response()->json([
                'success' => false,
                'message' => 'No device tokens found for matching users'
            ], 404);
        }

        $failed = [];
        $sent = 0;

        foreach ($tokens as $token) {
            try {
                $this->fcm->sendNotification(
                    $token,
                    $request->title,
                    $request->body,
                    $request->data ?? []
                );
                $sent++;
            } catch (\Throwable $e) {
                Log::error("Failed to send FCM to {$token}: ".$e->getMessage());
                $failed[] = $token;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Notifications sent based on preferences',
            'matched_preferences' => $preferences->count(),
            'matched_users' => $userIds->count(),
            'sent' => $sent,
            'failed' => count($failed),
            'failed_tokens' => $failed
        ]);
    }

    /**
     * Send notification to users with specific preference IDs
     */
    public function sendToPreferenceUsers(Request $request)
    {
        $request->validate([
            'preference_ids' => 'required|array',
            'preference_ids.*' => 'integer|exists:user_preferences,id',
            'title' => 'required|string',
            'body' => 'required|string',
            'data' => 'nullable|array'
        ]);

        $preferences = UserPreference::whereIn('id', $request->preference_ids)
            ->where('is_active', true)
            ->get();

        if ($preferences->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No active preferences found'
            ], 404);
        }

        $userIds = $preferences->pluck('user_id')->filter()->unique();
        $tokens = DeviceToken::whereIn('user_id', $userIds)
            ->pluck('device_token')
            ->toArray();

        if (empty($tokens)) {
            return response()->json([
                'success' => false,
                'message' => 'No device tokens found for preference users'
            ], 404);
        }

        $failed = [];
        $sent = 0;

        foreach ($tokens as $token) {
            try {
                $this->fcm->sendNotification(
                    $token,
                    $request->title,
                    $request->body,
                    $request->data ?? []
                );
                $sent++;
            } catch (\Throwable $e) {
                Log::error("Failed to send FCM to {$token}: ".$e->getMessage());
                $failed[] = $token;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Notifications sent to preference users',
            'users_notified' => $userIds->count(),
            'sent' => $sent,
            'failed' => count($failed),
            'failed_tokens' => $failed
        ]);
    }
}
