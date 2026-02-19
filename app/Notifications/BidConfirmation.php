<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BidConfirmation extends Notification
{
    use Queueable;
    protected $bid;
    protected $url;

    public function __construct($bid)
    {
        $this->bid = $bid;
        $this->url = 'URL';
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }


    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Bid Approved')
            ->greeting('Hello ' . $this->bid->user->name)
            ->line('Your bid has been approved.Please Proceed')
            ->line('Amount :'.$this->bid->bid_amount)
            ->action('View', $this->url)
            ->line('Thank you!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Your Bid Approved',
            'name' => $this->bid->user->name,
            'email' => $this->bid->user->email,
            'message' =>'Your Bid Approved',
            'link' => $this->url,
            'created_at' => now(),

        ];
    }
}
