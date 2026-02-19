<?php

namespace App\Notifications;


use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VehicleInspectionConfirmation extends Notification
{
    use Queueable;
    public $enquiry;

    public $url;
    public $report_id;
    /**
     * Create a new notification instance.
     */
    public function __construct($enquiry)
    {
        $this->enquiry = $enquiry;
        $this->report_id = $enquiry->id;
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
             ->subject('Your Vehicle Inspection Report Has Been Generated')
            ->greeting('Hello ' . $this->enquiry->name)
            ->line('We are pleased to inform you that your Vehicle Inspection Report has been successfully generated.')
            ->line('Report ID: #' . $this->report_id)
            ->line('You can now view the details of your inspection report.')
             ->action('View ', $this->url)
            ->line('Thank you for choosing our service!');
    }
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Your Vehicle Inspection Report Has Been Generated',
            'name' => $this->enquiry->name,
            'email' => $this->enquiry->email,
            'message' =>'Your Vehicle Inspection Report Has Been Generated',
            'created_at' => now(),

        ];
    }
}
