<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VehicleEnquiryNotification extends Notification
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
            ->subject('New ' . $this->enquiry->type . ' Enquiry Submitted')
            ->greeting('Hello Admin,')
            ->line('A new enquiry has been submitted.')
            ->line('Name: ' . $this->enquiry->name)
            ->line('Email: ' . $this->enquiry->email)
            ->line('Message: ' . 'New ' . $this->enquiry->type . ' Submitted')
            ->action('View ' . $this->enquiry->type . ' Enquiry', $this->url)
            ->line('Thank you!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New enquiry submitted',
            'name' => $this->enquiry->name,
            'email' => $this->enquiry->email,
            'message' => 'New ' . $this->enquiry->type . ' Submitted',
            'link' => $this->url,
            'created_at' => now(),

        ];
    }
}
