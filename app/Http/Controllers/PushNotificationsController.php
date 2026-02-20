<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PushNotificationsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // This endpoint requires authentication, so if not authenticated, Laravel will return 401 automatically
        return response()->json(['notifications' => []]);
    }
}
