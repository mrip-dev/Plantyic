<?php

namespace App\Notifications;


use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VehicleEnquiryReceivedConfirmation extends Notification
{
    use Queueable;
    public $enquiry;
    public $url;
    /**
     * Create a new notification instance.
     */
    public function __construct($enquiry)
    {
        $this->enquiry = $enquiry;
        $this->url = 'URL';
       
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('We Have Received ' . $this->enquiry->type . ' Your Enquiry')
            ->greeting('Hello ' . $this->enquiry->name)
            ->line('Thank you for contacting us! We have successfully received your message and will get back to you as soon as possible.')
            ->line('Vehicle : ' . $this->enquiry->vehicle?->title)
             ->action('View '.$this->enquiry->type.' Enquiry', $this->url)
            ->line('Thank you for your patience.');
    }
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New ' . $this->enquiry->type . ' enquiry submitted',
            'name' => $this->enquiry->name,
            'email' => $this->enquiry->email,
            'message' => $this->enquiry->notes,
            'created_at' => now(),

        ];
    }
}
