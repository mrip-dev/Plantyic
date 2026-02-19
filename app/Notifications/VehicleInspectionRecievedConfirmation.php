<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VehicleInspectionRecievedConfirmation extends Notification
{
    use Queueable;
    protected $enquiry;
    protected $url;

    public function __construct($enquiry)
    {
        $this->enquiry = $enquiry;
         $this->url = 'URL';
       
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }


    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Inspection Enquiry Submitted')
            ->greeting('Hello ' . $this->enquiry->name)
            ->line('A new enquiry has been submitted.')
            ->line('Name: ' . $this->enquiry->name)
            ->line('Email: ' . $this->enquiry->email)
            ->action('View Inspection Enquiry', $this->url)
            ->line('Thank you!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New enquiry submitted',
            'name' => $this->enquiry->name,
            'email' => $this->enquiry->email,
            'message' =>'New Inspection Enquiry Submitted',
            'link' => $this->url,
            'created_at' => now(),

        ];
    }
}
