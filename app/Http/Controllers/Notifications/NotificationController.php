<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Http\Requests\Notifications\NotificationRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::all();
        return response()->json(['data' => $notifications]);
    }

    public function store(NotificationRequest $request, NotificationService $service)
    {
        $notification = $service->create($request->validated());
        return response()->json(['data' => $notification], 201);
    }

    public function show(Notification $notification)
    {
        return response()->json(['data' => $notification]);
    }

    public function update(NotificationRequest $request, Notification $notification, NotificationService $service)
    {
        $service->update($notification, $request->validated());
        return response()->json(['data' => $notification]);
    }

    public function destroy(Notification $notification, NotificationService $service)
    {
        $service->delete($notification);
        return response()->json(['message' => 'Notification deleted successfully']);
    }
}
