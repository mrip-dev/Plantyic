<?php

namespace App\Notifications;


use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnquiryReceivedConfirmation extends Notification
{
    use Queueable;
    public $enquiry;
    /**
     * Create a new notification instance.
     */
    public function __construct(ContactSubmission $enquiry)
    {
        $this->enquiry = $enquiry;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('We Have Received Your Enquiry')
                    ->greeting('Hello ' . $this->enquiry->first_name . ' ' . $this->enquiry->last_name)
                    ->line('Thank you for contacting us! We have successfully received your message and will get back to you as soon as possible.')
                    ->line('Thank you for your patience.');
    }


}
