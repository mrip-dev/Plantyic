<?php

namespace App\Notifications;


use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountCreatedConfirmation extends Notification
{
    use Queueable;
    public $user;
    public $loginUrl;
    public $tempPassword;
    /**
     * Create a new notification instance.
     */
    public function __construct($user,$tempPassword)
    {
        $this->user = $user;
        $this->tempPassword = $tempPassword;
        $this->loginUrl = 'URL';
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
            ->subject('Your Account Has Been Created')
            ->greeting('Hello ' . ($this->user->name ?? 'there') . ',')
            ->line('Your account has been created successfully.')
            ->line('You can log in using the email and temporary password below:')
            ->line('Email: ' . ($this->user->email ?? ''))
            ->line('Temporary Password: ' . $this->tempPassword)
            ->action('Login to Your Account', $this->loginUrl)
            ->line('For security, please change your password after logging in.')
            ->salutation('Regards, ' . config('app.name'));
    }

    /**
     * Store notification in database.
     */
    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Account Created',
            'message' => 'An account was created for ' . ($this->user->name ?? 'the user') . '.',
            'user_id' => $this->user->id ?? null,
            'name' => $this->user->name ?? null,
            'email' => $this->user->email ?? null,
            'login_url' => $this->loginUrl,
            // Do NOT store the password in DB notifications
            'created_at' => now(),
        ];
    }
}
