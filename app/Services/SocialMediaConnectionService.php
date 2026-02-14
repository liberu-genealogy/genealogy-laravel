<?php

namespace App\Services;

use App\Models\ConnectedAccount;
use App\Models\SocialConnectionPrivacy;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SocialMediaConnectionService
{
    /**
     * Enable family matching for a connected account.
     */
    public function enableFamilyMatching(ConnectedAccount $account): bool
    {
        try {
            $account->update(['enable_family_matching' => true]);
            
            // Trigger initial sync
            $this->syncAccountData($account);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to enable family matching', [
                'account_id' => $account->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Disable family matching for a connected account.
     */
    public function disableFamilyMatching(ConnectedAccount $account): bool
    {
        try {
            $account->update([
                'enable_family_matching' => false,
                'cached_profile_data' => null,
                'last_synced_at' => null,
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to disable family matching', [
                'account_id' => $account->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Sync account data from social media platform.
     */
    public function syncAccountData(ConnectedAccount $account): bool
    {
        if (!$account->enable_family_matching) {
            return false;
        }

        try {
            // Get fresh data from the provider
            $profileData = $this->fetchProfileData($account);
            
            $account->update([
                'cached_profile_data' => $profileData,
                'last_synced_at' => now(),
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to sync account data', [
                'account_id' => $account->id,
                'provider' => $account->provider,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Fetch profile data from social media provider.
     */
    protected function fetchProfileData(ConnectedAccount $account): array
    {
        // In a real implementation, this would use the provider's API
        // For now, we'll return basic data from the account
        return [
            'name' => $account->name,
            'email' => $account->email,
            'nickname' => $account->nickname,
            'provider' => $account->provider,
            'provider_id' => $account->provider_id,
            'fetched_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Get or create privacy settings for a user.
     */
    public function getOrCreatePrivacySettings(User $user): SocialConnectionPrivacy
    {
        return SocialConnectionPrivacy::firstOrCreate(
            ['user_id' => $user->id],
            [
                'allow_family_discovery' => true,
                'show_profile_to_matches' => true,
                'share_tree_with_matches' => false,
                'allow_contact_from_matches' => true,
            ]
        );
    }

    /**
     * Update privacy settings for a user.
     */
    public function updatePrivacySettings(User $user, array $settings): SocialConnectionPrivacy
    {
        $privacy = $this->getOrCreatePrivacySettings($user);
        $privacy->update($settings);
        
        return $privacy;
    }

    /**
     * Check if account needs to be synced.
     */
    public function needsSync(ConnectedAccount $account): bool
    {
        if (!$account->enable_family_matching) {
            return false;
        }

        if (!$account->last_synced_at) {
            return true;
        }

        // Sync if older than 24 hours
        return now()->diffInHours($account->last_synced_at) > 24;
    }

    /**
     * Disconnect a social account and clean up data.
     */
    public function disconnectAccount(ConnectedAccount $account): bool
    {
        try {
            // Delete related family connections
            $account->socialFamilyConnections()->delete();
            
            // Delete the account
            $account->delete();
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to disconnect account', [
                'account_id' => $account->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
