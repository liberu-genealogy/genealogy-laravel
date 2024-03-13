&lt;?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TeamInvitation;
use Illuminate\Support\Str;

class TeamInvitationController extends Controller
{
        /**
     * Send an invitation to join the team.
     *
     * This method handles the creation and sending of an invitation to a prospective team member.
     * 
     * @param Request $request The request object containing the email and team details.
     * @return Response Returns a response object indicating the success or failure of the operation.
     * @throws Exception Throws an exception if the invitation could not be sent.
     */
    public function sendInvitation(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'team_id' => 'required|exists:teams,id',
        ]);

        $user = User::firstOrCreate(['email' => $request->email], ['password' => bcrypt(Str::random(10))]);
        $team = Team::findOrFail($request->team_id);

        $invitationToken = Str::random(32);
        $user->invitations()->create([
            'team_id' => $team->id,
            'token' => $invitationToken,
        ]);

        Mail::to($request->email)->send(new TeamInvitation($user, $team, $invitationToken));

        return response()->json(['message' => 'Invitation sent successfully.']);
    }

        /**
     * Accept an invitation to join the team.
     *
     * This method allows a user to accept an invitation to join a team by validating the provided token.
     * It adds the user as a team member upon successful validation.
     * 
     * @param Request $request The request object containing the invitation token.
     * @return Response Returns a response object indicating the acceptance of the invitation and joining the team.
     * @throws ValidationException If the provided token doesn't exist or is invalid.
     */
    public function acceptInvitation(Request $request)
    {
        $request->validate([
            'token' => 'required|exists:invitations,token',
        ]);

        $invitation = Invitation::where('token', $request->token)->firstOrFail();
        $team = Team::findOrFail($invitation->team_id);
        $team->members()->attach($invitation->user_id);

        $invitation->update(['accepted' => true]);

        return response()->json(['message' => 'Invitation accepted successfully.']);
    }
}
