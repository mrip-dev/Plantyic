<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnquirySubmitNotification extends Notification
{
    use Queueable;
    protected $enquiry;

    public function __construct($enquiry)
    {
        $this->enquiry = $enquiry;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }


    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Contact Us Enquiry Submitted')
            ->greeting('Hello Admin,')
            ->line('A new enquiry has been submitted.')
            ->line('Name: ' . $this->enquiry->first_name . ' ' . $this->enquiry->last_name)
            ->line('Email: ' . $this->enquiry->email)
            ->line('Message: ' . $this->enquiry->message)
            ->action('View Enquiries', url('admin/submissions'))
            ->line('Thank you!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Contact Us enquiry submitted',
            'name' => $this->enquiry->first_name . ' ' . $this->enquiry->last_name,
            'email' => $this->enquiry->email,
            'message' => $this->enquiry->message,
            'link' => 'URL',
            'created_at' => now(),

        ];
    }
}
