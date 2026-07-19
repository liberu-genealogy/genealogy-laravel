<?php

namespace App\Filament\App\Pages;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class PrivateMessagingPage extends Page
{
    #[\Override]
    protected string $view = 'filament.app.pages.private-messaging-page';

    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    #[\Override]
    protected static ?string $navigationLabel = 'Private Messaging';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '👤 Account & Settings';

    public ?int $selectedUserId = null;

    public string $messageText = '';

    public $users = [];

    /**
     * Chat messages for the selected conversation.
     *
     * NOT named $messages: Livewire's HandlesValidation reads a component
     * property called `messages` as custom validation messages, so assigning a
     * Collection to it made every validate() call die with "array_merge():
     * Argument #1 must be of type array". sendMessage() validates first, so it
     * threw before storing anything and private messaging never worked.
     */
    public $conversationMessages = [];

    public function mount(): void
    {
        $this->selectedUserId = request()->query('user_id') ? (int) request()->query('user_id') : null;
        $this->users = $this->addressableUsers();
        $this->loadMessages();
    }

    /**
     * Users the signed-in user may message: members of the teams they share.
     *
     * This previously loaded every user in the installation, so the recipient
     * picker enumerated the whole user base across tenants and the Livewire
     * snapshot carried that list on every roundtrip. It went unnoticed because
     * sendMessage() threw before reaching anyone (see $conversationMessages).
     */
    protected function addressableUsers(): Collection
    {
        $teamIds = Auth::user()->allTeams()->pluck('id');

        return User::whereKeyNot(Auth::id())
            ->where(function ($query) use ($teamIds): void {
                $query->whereIn('current_team_id', $teamIds)
                    ->orWhereHas('teams', fn ($q) => $q->whereIn('teams.id', $teamIds));
            })
            ->get();
    }

    protected function mayMessage(int $userId): bool
    {
        return $this->addressableUsers()->contains('id', $userId);
    }

    public function updatedSelectedUserId(): void
    {
        $this->loadMessages();
    }

    public function loadMessages(): void
    {
        if (! $this->selectedUserId) {
            $this->conversationMessages = collect();

            return;
        }

        $conversation = $this->findConversation();

        if (! $conversation instanceof Conversation) {
            $this->conversationMessages = collect();

            return;
        }

        $this->conversationMessages = Message::where('conversation_id', $conversation->id)
            ->orderBy('created_at')
            ->get();
    }

    public function sendMessage(): void
    {
        $this->validate([
            'messageText' => 'required|string',
            'selectedUserId' => 'required|integer|exists:users,id',
        ]);

        // selectedUserId is a client-settable public property, so exists: alone
        // would let any authenticated user open a conversation with anyone in
        // the installation, across tenants.
        if (! $this->mayMessage($this->selectedUserId)) {
            $this->addError('selectedUserId', 'You cannot message that user.');

            return;
        }

        $conversation = $this->findOrCreateConversation();

        Message::create([
            'message' => $this->messageText,
            'user_id' => Auth::id(),
            'conversation_id' => $conversation->id,
        ]);

        $this->messageText = '';
        $this->loadMessages();
    }

    private function findConversation(): ?Conversation
    {
        return Conversation::where(function ($q): void {
            $q->where('user_one', Auth::id())
                ->where('user_two', $this->selectedUserId);
        })->orWhere(function ($q): void {
            $q->where('user_one', $this->selectedUserId)
                ->where('user_two', Auth::id());
        })->first();
    }

    private function findOrCreateConversation(): Conversation
    {
        return $this->findConversation() ?? Conversation::create([
            'user_one' => Auth::id(),
            'user_two' => $this->selectedUserId,
        ]);
    }
}
