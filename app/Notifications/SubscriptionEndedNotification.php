<?php

namespace App\Notifications;

use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionEndedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your premium subscription has ended')
            ->greeting('Subscription ended')
            ->line('Your premium subscription has ended and your account is now on the free tier.')
            ->line('All your family tree data is kept — you can resubscribe any time to restore premium features.')
            ->action('Resubscribe', url('/app'))
            ->line('Thanks for being a premium member.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Subscription ended')
            ->icon('heroicon-o-x-circle')
            ->body('Your premium subscription has ended. Your data is kept; resubscribe any time.')
            ->getDatabaseMessage() + [
                'type' => 'subscription_ended',
            ];
    }
}
