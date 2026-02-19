<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use GuzzleHttp\Client;

class FCMService
{
    protected $messaging;

    public function __construct()
    {
        // Disable SSL verification for Windows dev
        $guzzle = new Client(['verify' => false]);

        $factory = (new Factory())
            ->withServiceAccount(env('FIREBASE_CREDENTIALS'));

        // Only set client if your version supports it
        if (method_exists($factory, 'withHttpClient')) {
            $factory = $factory->withHttpClient($guzzle);
        }

        $this->messaging = $factory->createMessaging();
    }

    public function sendNotification($deviceToken, $title, $body, $data = [])
    {
        $notification = Notification::create($title, $body);

        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification($notification)
            ->withData($data);

        return $this->messaging->send($message);
    }
}
