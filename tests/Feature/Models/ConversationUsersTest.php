<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Conversation::users() called $this->userOne->merge(...). userOne is a belongsTo,
 * so it resolves to a single User, and merge() is a Collection method — the call
 * threw BadMethodCallException for anyone who reached it. Nothing did.
 */
class ConversationUsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_returns_both_participants(): void
    {
        $one = User::factory()->create();
        $two = User::factory()->create();

        $conversation = Conversation::create([
            'user_one' => $one->id,
            'user_two' => $two->id,
            'status' => 1,
        ]);

        $users = $conversation->users();

        $this->assertCount(2, $users);
        $this->assertEqualsCanonicalizing(
            [$one->id, $two->id],
            $users->pluck('id')->all()
        );
    }
}
