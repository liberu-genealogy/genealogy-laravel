<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\ConnectedAccount;
use App\Models\SocialConnectionPrivacy;
use App\Models\SocialFamilyConnection;
use App\Services\FamilyMatchingService;
use App\Services\SocialMediaConnectionService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Attributes\On;

final class SocialConnections extends Component
{
    public array $privacySettings = [];
    public ?Collection $connectedAccounts = null;
    public ?Collection $pendingConnections = null;
    public ?Collection $acceptedConnections = null;
    public bool $isLoading = false;

    protected SocialMediaConnectionService $socialService;
    protected FamilyMatchingService $matchingService;

    public function boot(
        SocialMediaConnectionService $socialService,
        FamilyMatchingService $matchingService
    ): void {
        $this->socialService = $socialService;
        $this->matchingService = $matchingService;
    }

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $user = Auth::user();
        
        // Load privacy settings
        $privacy = $this->socialService->getOrCreatePrivacySettings($user);
        $this->privacySettings = [
            'allow_family_discovery' => $privacy->allow_family_discovery,
            'show_profile_to_matches' => $privacy->show_profile_to_matches,
            'share_tree_with_matches' => $privacy->share_tree_with_matches,
            'allow_contact_from_matches' => $privacy->allow_contact_from_matches,
        ];

        // Load connected accounts
        $this->connectedAccounts = ConnectedAccount::where('user_id', $user->id)->get();

        // Load pending connections
        $this->pendingConnections = SocialFamilyConnection::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with('connectedAccount')
            ->orderBy('confidence_score', 'desc')
            ->get();

        // Load accepted connections
        $this->acceptedConnections = SocialFamilyConnection::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->with('connectedAccount')
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public function updatePrivacySettings(): void
    {
        try {
            $user = Auth::user();
            $this->socialService->updatePrivacySettings($user, $this->privacySettings);
            
            session()->flash('message', 'Privacy settings updated successfully!');
            $this->dispatch('privacy-updated');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update privacy settings: ' . $e->getMessage());
        }
    }

    public function toggleFamilyMatching(int $accountId): void
    {
        try {
            $account = ConnectedAccount::findOrFail($accountId);
            
            if (!$account->enable_family_matching) {
                $this->socialService->enableFamilyMatching($account);
                session()->flash('message', 'Family matching enabled for ' . $account->provider);
            } else {
                $this->socialService->disableFamilyMatching($account);
                session()->flash('message', 'Family matching disabled for ' . $account->provider);
            }
            
            $this->loadData();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to toggle family matching: ' . $e->getMessage());
        }
    }

    public function syncAccount(int $accountId): void
    {
        $this->isLoading = true;
        
        try {
            $account = ConnectedAccount::findOrFail($accountId);
            $this->socialService->syncAccountData($account);
            
            session()->flash('message', 'Account synced successfully!');
            $this->loadData();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to sync account: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    public function findMatches(): void
    {
        $this->isLoading = true;
        
        try {
            $user = Auth::user();
            $count = $this->matchingService->processMatches($user);
            
            session()->flash('message', "Found {$count} potential family connection(s)!");
            $this->loadData();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to find matches: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    public function acceptConnection(int $connectionId): void
    {
        try {
            $connection = SocialFamilyConnection::findOrFail($connectionId);
            $connection->accept();
            
            session()->flash('message', 'Connection accepted!');
            $this->loadData();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to accept connection: ' . $e->getMessage());
        }
    }

    public function rejectConnection(int $connectionId): void
    {
        try {
            $connection = SocialFamilyConnection::findOrFail($connectionId);
            $connection->reject();
            
            session()->flash('message', 'Connection rejected.');
            $this->loadData();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to reject connection: ' . $e->getMessage());
        }
    }

    public function disconnectAccount(int $accountId): void
    {
        try {
            $account = ConnectedAccount::findOrFail($accountId);
            $this->socialService->disconnectAccount($account);
            
            session()->flash('message', 'Account disconnected successfully!');
            $this->loadData();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to disconnect account: ' . $e->getMessage());
        }
    }

    #[On('account-connected')]
    public function handleAccountConnected(): void
    {
        $this->loadData();
        session()->flash('message', 'Account connected successfully!');
    }

    public function render(): View
    {
        return view('livewire.social-connections');
    }
}
