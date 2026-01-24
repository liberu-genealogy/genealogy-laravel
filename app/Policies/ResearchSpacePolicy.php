<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ResearchSpace;
use App\Models\ResearchSpaceCollaborator;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResearchSpacePolicy
{
    use HandlesAuthorization;

    public function view(User $user, ResearchSpace $space): bool
    {
        if ($space->owner_id === $user->id) {
            return true;
        }

        return ResearchSpaceCollaborator::where('research_space_id', $space->id)
            ->where('user_id', $user->id)
            ->exists();
    }

    public function update(User $user, ResearchSpace $space): bool
    {
        if ($space->owner_id === $user->id) {
            return true;
        }

        $collab = ResearchSpaceCollaborator::where('research_space_id', $space->id)
            ->where('user_id', $user->id)
            ->first();

        if (! $collab) {
            return false;
        }

        return in_array($collab->role, ['owner', 'admin', 'editor']);
    }

    public function manageCollaborators(User $user, ResearchSpace $space): bool
    {
        if ($space->owner_id === $user->id) {
            return true;
        }

        $collab = ResearchSpaceCollaborator::where('research_space_id', $space->id)
            ->where('user_id', $user->id)
            ->first();

        if (! $collab) {
            return false;
        }

        return in_array($collab->role, ['owner', 'admin']);
    }
}
