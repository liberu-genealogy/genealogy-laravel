<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\App\Pages\PrivateMessagingPage;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * There were two PrivateMessagingPage classes. The real one, in the App panel,
 * is fully implemented — it finds or creates a conversation, validates, and
 * stores a message. The other was a bare stub in App\Filament\Pages with two
 * empty method bodies and a comment conceding it existed "to satisfy unit
 * tests".
 *
 * Its test asserted that a void method returns null, so it passed while proving
 * nothing, and the real page had no coverage at all. Deleting the stub without
 * replacing that coverage would swap one untruth for a genuine gap, so these
 * tests exercise the page that actually runs.
 */
class PrivateMessagingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_sending_a_message_creates_a_conversation_and_stores_it(): void
    {
        $sender = User::factory()->withPersonalTeam()->create();
        $recipient = $this->teammateOf($sender);
        $this->actingAs($sender);

        Livewire::test(PrivateMessagingPage::class)
            ->set('selectedUserId', $recipient->id)
            ->set('messageText', 'Do you have the 1881 census entry?')
            ->call('sendMessage')
            ->assertHasNoErrors();

        $conversation = Conversation::first();
        $this->assertNotNull($conversation);
        $this->assertSame($sender->id, $conversation->user_one);
        $this->assertSame($recipient->id, $conversation->user_two);

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'user_id' => $sender->id,
            'message' => 'Do you have the 1881 census entry?',
        ]);
    }

    public function test_a_reply_reuses_the_existing_conversation_in_either_direction(): void
    {
        $sender = User::factory()->withPersonalTeam()->create();
        $recipient = $this->teammateOf($sender);

        // Opened by the other party, so the columns are the reverse way round.
        Conversation::create(['user_one' => $recipient->id, 'user_two' => $sender->id]);

        $this->actingAs($sender);

        Livewire::test(PrivateMessagingPage::class)
            ->set('selectedUserId', $recipient->id)
            ->set('messageText', 'Found it, thank you.')
            ->call('sendMessage')
            ->assertHasNoErrors();

        $this->assertSame(1, Conversation::count(), 'A duplicate conversation was created.');
    }

    public function test_an_empty_message_is_rejected_and_stores_nothing(): void
    {
        $sender = User::factory()->withPersonalTeam()->create();
        $recipient = $this->teammateOf($sender);
        $this->actingAs($sender);

        Livewire::test(PrivateMessagingPage::class)
            ->set('selectedUserId', $recipient->id)
            ->set('messageText', '')
            ->call('sendMessage')
            ->assertHasErrors('messageText');

        $this->assertSame(0, Message::count());
    }

    /**
     * selectedUserId is a client-settable public property, so validating only
     * `exists:users,id` let any authenticated user open a conversation with
     * anyone in the installation, across tenants. It went unnoticed because
     * sendMessage() threw before it ever got that far.
     */
    public function test_a_user_outside_your_teams_cannot_be_messaged(): void
    {
        $sender = User::factory()->withPersonalTeam()->create();
        $stranger = User::factory()->withPersonalTeam()->create();
        $this->actingAs($sender);

        Livewire::test(PrivateMessagingPage::class)
            ->set('selectedUserId', $stranger->id)
            ->set('messageText', 'Can you see this?')
            ->call('sendMessage')
            ->assertHasErrors('selectedUserId');

        $this->assertSame(0, Message::count());
        $this->assertSame(0, Conversation::count());
    }

    public function test_the_recipient_picker_lists_only_teammates(): void
    {
        $sender = User::factory()->withPersonalTeam()->create();
        $teammate = $this->teammateOf($sender);
        $stranger = User::factory()->withPersonalTeam()->create();
        $this->actingAs($sender);

        $listed = Livewire::test(PrivateMessagingPage::class)->get('users')->pluck('id');

        $this->assertTrue($listed->contains($teammate->id));
        $this->assertFalse($listed->contains($stranger->id), 'A user from another team was listed.');
        $this->assertFalse($listed->contains($sender->id), 'The sender listed themselves.');
    }

    public function test_messages_load_for_the_selected_conversation_only(): void
    {
        $sender = User::factory()->withPersonalTeam()->create();
        $recipient = $this->teammateOf($sender);
        $stranger = User::factory()->withPersonalTeam()->create();
        $this->actingAs($sender);

        $ours = Conversation::create(['user_one' => $sender->id, 'user_two' => $recipient->id]);
        $theirs = Conversation::create(['user_one' => $recipient->id, 'user_two' => $stranger->id]);

        Message::create(['message' => 'ours', 'user_id' => $sender->id, 'conversation_id' => $ours->id]);
        Message::create(['message' => 'theirs', 'user_id' => $recipient->id, 'conversation_id' => $theirs->id]);

        $component = Livewire::test(PrivateMessagingPage::class)
            ->set('selectedUserId', $recipient->id);

        $messages = $component->get('conversationMessages');

        $this->assertCount(1, $messages);
        $this->assertSame('ours', $messages->first()->message);
    }

    private function teammateOf(User $user): User
    {
        $teammate = User::factory()->create();
        $user->currentTeam->users()->attach($teammate, ['role' => 'editor']);

        return $teammate->fresh();
    }
}
