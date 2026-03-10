<?php

namespace App\Filament\App\Pages;

use App\Models\Message;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class PrivateMessagingPage extends Page
{
    protected string $view = 'filament.app.pages.private-messaging-page';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Private Messaging';
    protected static string | \UnitEnum | null $navigationGroup = '👤 Account & Settings';

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

        $this->messages = Message::where(function ($query) {
            $query->where('from_user_id', Auth::id())
                ->where('to_user_id', $this->selectedUserId);
        })->orWhere(function ($query) {
            $query->where('from_user_id', $this->selectedUserId)
                ->where('to_user_id', Auth::id());
        })->orderBy('created_at')->get();
    }

    public function sendMessage(): void
    {
        $this->validate([
            'messageText' => 'required|string',
            'selectedUserId' => 'required|integer|exists:users,id',
        ]);

        $message = new Message();
        $message->from_user_id = Auth::id();
        $message->to_user_id = $this->selectedUserId;
        $message->message = $this->messageText;
        $message->save();

        $this->messageText = '';
        $this->loadMessages();
    }
}
