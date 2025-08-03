<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\UserMembership;

class MembershipRenewed extends Notification
{
    use Queueable;

    public $userMembership;

    /**
     * Create a new notification instance.
     */
    public function __construct(UserMembership $userMembership)
    {
        $this->userMembership = $userMembership;
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
                    ->subject('Membership Renewed')
                    ->greeting('Hello ' . $this->userMembership->user->name . ',')
                    ->line('Your membership has been renewed.')
                    ->line('Membership: ' . $this->userMembership->membership->name)
                    ->line('Start Date: ' . $this->userMembership->membership_start_date->format('d M Y'))
                    ->line('Expiry Date: ' . $this->userMembership->membership_expiry_date->format('d M Y'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
