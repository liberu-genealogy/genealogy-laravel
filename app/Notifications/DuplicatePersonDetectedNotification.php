<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DuplicatePersonDetectedNotification extends Notification implements ShouldQueue
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
        $noun = $this->count === 1 ? 'a possible duplicate person' : "{$this->count} possible duplicate people";

        return (new MailMessage)
            ->subject('Possible duplicate people detected')
            ->greeting('Heads up!')
            ->line("Our scan found {$noun} in your family tree.")
            ->action('Review duplicates', url('/app'))
            ->line('Merging duplicates keeps your tree accurate.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'duplicate_person_detected',
            'count' => $this->count,
            'message' => "{$this->count} possible duplicate person(s) detected.",
        ];
    }
}
