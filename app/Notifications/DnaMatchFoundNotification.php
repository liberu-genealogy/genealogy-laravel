<?php

namespace App\Notifications;

use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DnaMatchFoundNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected int $matchCount) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $noun = $this->matchCount === 1 ? 'match' : 'matches';

        return (new MailMessage)
            ->subject('New DNA matches found')
            ->greeting('Good news!')
            ->line("We found {$this->matchCount} new DNA {$noun} for your kit.")
            ->action('Review your DNA matches', url('/app'))
            ->line('Sign in to explore shared segments and predicted relationships.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $noun = $this->matchCount === 1 ? 'match' : 'matches';

        return FilamentNotification::make()
            ->title('New DNA matches found')
            ->icon('heroicon-o-puzzle-piece')
            ->body("{$this->matchCount} new DNA {$noun} for your kit.")
            ->getDatabaseMessage() + [
                'type' => 'dna_match_found',
                'count' => $this->matchCount,
            ];
    }
}
