<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\ResearchSpace;
use App\Models\ResearchSpaceCollaborator;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', fn($user, $id): bool => (int) $user->id === (int) $id);

// Channel for ResearchSpace real-time updates.
// A user may listen if they are the owner or a collaborator on the space.
Broadcast::channel('research-space.{id}', function ($user, $id) {
    try {
        $space = ResearchSpace::find($id);
        if (! $space) {
            return false;
        }
        if ($space->owner_id === $user->id) {
            return true;
        }

        return ResearchSpaceCollaborator::where('research_space_id', $id)
            ->where('user_id', $user->id)
            ->exists();
    } catch (\Throwable $e) {
        return false;
    }
});
