<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created_with_factory(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_user_password_is_stored(): void
    {
        $user = User::factory()->create();

        $this->assertNotEmpty($user->password);
    }

    public function test_user_email_is_unique(): void
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        User::factory()->create(['email' => 'test@example.com']);
    }

    public function test_user_has_teams(): void
    {
        $user = User::factory()->withPersonalTeam()->create();

        $this->assertNotEmpty($user->allTeams());
    }

    public function test_user_has_api_tokens(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('test-token');

        $this->assertNotNull($token);
        $this->assertSame(1, $user->tokens()->count());
    }

    public function test_user_hidden_attributes(): void
    {
        $user = User::factory()->create();
        $array = $user->toArray();

        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }
}
