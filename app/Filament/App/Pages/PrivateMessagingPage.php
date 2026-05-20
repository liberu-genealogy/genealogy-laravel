<?php

namespace App\Filament\App\Pages;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class PrivateMessagingPage extends Page
{
    protected string $view = 'filament.app.pages.private-messaging-page';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Private Messaging';

    protected static string|\UnitEnum|null $navigationGroup = '👤 Account & Settings';

    public ?int $selectedUserId = null;

    public string $messageText = '';

    public $users = [];

    public $messages = [];

    public function mount(): void
    {
        $this->selectedUserId = request()->query('user_id') ? (int) request()->query('user_id') : null;
        $this->users = User::where('id', '!=', Auth::id())->get();
        $this->loadMessages();
    }

    public function updatedSelectedUserId(): void
    {
        $this->loadMessages();
    }

    public function loadMessages(): void
    {
        if (! $this->selectedUserId) {
            $this->messages = collect();

            return;
        }

        $conversation = $this->findConversation();

        if (! $conversation) {
            $this->messages = collect();

            return;
        }

        $this->messages = Message::where('conversation_id', $conversation->id)
            ->orderBy('created_at')
            ->get();
    }

    public function sendMessage(): void
    {
        $this->validate([
            'messageText' => 'required|string',
            'selectedUserId' => 'required|integer|exists:users,id',
        ]);

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
        return Conversation::where(function ($q) {
            $q->where('user_one', Auth::id())
                ->where('user_two', $this->selectedUserId);
        })->orWhere(function ($q) {
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
