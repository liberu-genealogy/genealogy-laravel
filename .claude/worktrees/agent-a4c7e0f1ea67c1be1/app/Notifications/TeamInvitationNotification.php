&lt;?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Team;

class TeamInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $team;
    protected $invitationToken;

    public function __construct(User $user, Team $team, $invitationToken)
    {
        $this->user = $user;
        $this->team = $team;
        $this->invitationToken = $invitationToken;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('You have been invited to join the team: ' . $this->team->name . '.')
                    ->action('Accept Invitation', route('team.invitations.accept', ['token' => $this->invitationToken]))
                    ->line('If you did not request this invitation, no further action is required.');
    }

    public function toArray($notifiable)
    {
        return [
            'team_name' => $this->team->name,
            'invitation_token' => $this->invitationToken,
        ];
    }
}
