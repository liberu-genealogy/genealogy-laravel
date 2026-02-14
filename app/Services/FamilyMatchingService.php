<?php

namespace App\Services;

use App\Models\ConnectedAccount;
use App\Models\Person;
use App\Models\SocialConnectionPrivacy;
use App\Models\SocialFamilyConnection;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class FamilyMatchingService
{
    /**
     * Find potential family connections for a user.
     */
    public function findPotentialConnections(User $user): Collection
    {
        $privacy = SocialConnectionPrivacy::where('user_id', $user->id)->first();
        
        if (!$privacy || !$privacy->allow_family_discovery) {
            return collect();
        }

        $matches = collect();

        // Get user's connected accounts that have family matching enabled
        $connectedAccounts = ConnectedAccount::where('user_id', $user->id)
            ->where('enable_family_matching', true)
            ->get();

        foreach ($connectedAccounts as $account) {
            $accountMatches = $this->findMatchesForAccount($user, $account);
            $matches = $matches->merge($accountMatches);
        }

        return $matches;
    }

    /**
     * Find matches for a specific connected account.
     */
    protected function findMatchesForAccount(User $user, ConnectedAccount $account): Collection
    {
        $matches = collect();
        $profileData = $account->cached_profile_data;

        if (!$profileData) {
            return $matches;
        }

        // Get user's family tree data
        $familySurnames = $this->getUserFamilySurnames($user);
        
        // Find other users with matching criteria
        $potentialMatches = $this->findUsersWithMatchingData($familySurnames, $account->provider);

        foreach ($potentialMatches as $match) {
            // Check if this match already exists
            $existing = SocialFamilyConnection::where('user_id', $user->id)
                ->where('connected_account_id', $account->id)
                ->where('matched_social_id', $match['social_id'])
                ->first();

            if (!$existing) {
                $matches->push($match);
            }
        }

        return $matches;
    }

    /**
     * Get unique surnames from user's family tree.
     */
    protected function getUserFamilySurnames(User $user): array
    {
        // Get persons associated with user's teams
        $surnames = Person::whereHas('gedcom', function ($query) use ($user) {
            $query->whereIn('team_id', $user->allTeams()->pluck('id'));
        })
        ->whereNotNull('surname')
        ->distinct()
        ->pluck('surname')
        ->toArray();

        return array_filter($surnames);
    }

    /**
     * Find users with matching family data on the same social platform.
     */
    protected function findUsersWithMatchingData(array $surnames, string $provider): Collection
    {
        $matches = collect();

        // Find other connected accounts on the same provider
        $otherAccounts = ConnectedAccount::where('provider', $provider)
            ->where('enable_family_matching', true)
            ->whereNotNull('cached_profile_data')
            ->get();

        foreach ($otherAccounts as $otherAccount) {
            $otherUserSurnames = $this->getUserFamilySurnames($otherAccount->user);
            
            // Find common surnames
            $commonSurnames = array_intersect($surnames, $otherUserSurnames);
            
            if (!empty($commonSurnames)) {
                // Check privacy settings of the other user
                $otherPrivacy = SocialConnectionPrivacy::where('user_id', $otherAccount->user_id)->first();
                
                if ($otherPrivacy && $otherPrivacy->allow_family_discovery) {
                    $matches->push([
                        'user_id' => $otherAccount->user_id,
                        'social_id' => $otherAccount->provider_id,
                        'name' => $otherAccount->name,
                        'email' => $otherAccount->email,
                        'common_surnames' => $commonSurnames,
                        'confidence_score' => $this->calculateConfidenceScore($commonSurnames),
                    ]);
                }
            }
        }

        return $matches;
    }

    /**
     * Calculate confidence score based on matching criteria.
     */
    protected function calculateConfidenceScore(array $commonSurnames): int
    {
        // Simple scoring: 20 points per common surname, max 100
        return min(100, count($commonSurnames) * 20);
    }

    /**
     * Create a family connection from a match.
     */
    public function createConnection(
        User $user,
        ConnectedAccount $account,
        array $matchData
    ): SocialFamilyConnection {
        return SocialFamilyConnection::create([
            'user_id' => $user->id,
            'connected_account_id' => $account->id,
            'matched_social_id' => $matchData['social_id'],
            'matched_name' => $matchData['name'] ?? null,
            'matched_email' => $matchData['email'] ?? null,
            'relationship_type' => 'potential_relative',
            'confidence_score' => $matchData['confidence_score'] ?? 0,
            'matching_criteria' => [
                'common_surnames' => $matchData['common_surnames'] ?? [],
            ],
            'status' => 'pending',
        ]);
    }

    /**
     * Process matches and create connections.
     */
    public function processMatches(User $user): int
    {
        $count = 0;

        try {
            $matches = $this->findPotentialConnections($user);

            $connectedAccounts = ConnectedAccount::where('user_id', $user->id)
                ->where('enable_family_matching', true)
                ->get()
                ->keyBy('id');

            foreach ($matches as $match) {
                // Find the appropriate connected account
                $account = $connectedAccounts->first(function ($acc) use ($match) {
                    return $acc->provider_id === $match['social_id'];
                });

                if ($account) {
                    $this->createConnection($user, $account, $match);
                    $count++;
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to process matches', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $count;
    }
}
