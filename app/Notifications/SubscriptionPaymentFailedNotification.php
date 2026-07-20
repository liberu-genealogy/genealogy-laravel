<?php

namespace App\Notifications;

use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionPaymentFailedNotification extends Notification implements ShouldQueue
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
            ->subject('Your subscription payment failed')
            ->greeting('Action needed')
            ->line('We could not process the payment for your premium subscription.')
            ->line('Stripe will retry automatically, but updating your card now avoids losing access.')
            ->action('Update your card', url('/app'))
            ->line('If your card is fine, you can ignore this — the retry may already have succeeded.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Payment failed')
            ->icon('heroicon-o-exclamation-triangle')
            ->body('Your premium subscription payment failed. Update your card to keep access.')
            ->getDatabaseMessage() + [
                'type' => 'subscription_payment_failed',
            ];
    }
}
