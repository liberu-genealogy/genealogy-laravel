<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RecordSuggestionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected int $count) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $noun = $this->count === 1 ? 'a new record suggestion' : "{$this->count} new record suggestions";

        return (new MailMessage)
            ->subject('New record suggestions')
            ->greeting('New leads!')
            ->line("We found {$noun} for people in your tree.")
            ->action('Review suggestions', url('/app'))
            ->line('Confirm or dismiss suggestions to improve future matches.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'record_suggestion',
            'count' => $this->count,
            'message' => "{$this->count} new record suggestion(s) found.",
        ];
    }
}
