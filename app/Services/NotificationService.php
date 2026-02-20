<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public function create(array $data): Notification
    {
        return Notification::create($data);
    }

    public function update(Notification $notification, array $data): Notification
    {
        $notification->update($data);
        return $notification;
    }

    public function delete(Notification $notification): void
    {
        $notification->delete();
    }
}
