<?php

declare(strict_types=1);

namespace Tests\Feature\Actions;

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateNewUserTest extends TestCase
{
    private CreateNewUser $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new CreateNewUser;

        // Deliberately does NOT create a panel_user role. It used to, which is
        // why this suite stayed green while every real registration returned
        // 500: RolesSeeder seeds only super_admin, so the role existed in tests
        // and nowhere else. The test manufactured the precondition production
        // lacked. CreateNewUser no longer assigns it.
    }

    public function test_user_can_be_created_with_valid_data(): void
    {
        $user = $this->action->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertTrue(Hash::check('Password1!', $user->password));
    }

    public function test_user_is_persisted_to_database(): void
    {
        $this->action->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
        ]);
    }

    public function test_personal_team_is_created_for_new_user(): void
    {
        $user = $this->action->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $this->assertCount(1, $user->ownedTeams);
        $this->assertTrue($user->ownedTeams->first()->personal_team);
    }

    public function test_personal_team_name_is_derived_from_user_name(): void
    {
        $user = $this->action->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $this->assertEquals("John's Team", $user->ownedTeams->first()->name);
    }

    public function test_user_is_switched_to_personal_team_after_creation(): void
    {
        $user = $this->action->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $this->assertNotNull($user->currentTeam);
        $this->assertEquals($user->ownedTeams->first()->id, $user->currentTeam->id);
    }

    /**
     * Replaces test_user_is_assigned_panel_user_role, which asserted the line
     * that broke registration. This asserts what actually has to hold: a new
     * account can be created without any role being seeded, because
     * User::canAccessPanel() grants the app panel to any authenticated user.
     */
    public function test_user_is_created_without_requiring_a_seeded_role(): void
    {
        $user = $this->action->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $this->assertTrue($user->exists);
        $this->assertCount(0, $user->roles);
    }

    public function test_validation_fails_when_name_is_missing(): void
    {
        $this->expectException(ValidationException::class);

        $this->action->create([
            'name' => '',
            'email' => 'john@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);
    }

    public function test_validation_fails_when_email_is_missing(): void
    {
        $this->expectException(ValidationException::class);

        $this->action->create([
            'name' => 'John Doe',
            'email' => '',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);
    }

    public function test_validation_fails_when_email_is_invalid(): void
    {
        $this->expectException(ValidationException::class);

        $this->action->create([
            'name' => 'John Doe',
            'email' => 'not-an-email',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);
    }

    public function test_validation_fails_when_email_is_already_taken(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $this->expectException(ValidationException::class);

        $this->action->create([
            'name' => 'John Doe',
            'email' => 'existing@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);
    }

    public function test_validation_fails_when_password_is_missing(): void
    {
        $this->expectException(ValidationException::class);

        $this->action->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);
    }

    public function test_validation_fails_when_passwords_do_not_match(): void
    {
        $this->expectException(ValidationException::class);

        $this->action->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'DifferentPassword1!',
        ]);
    }

    public function test_validation_fails_when_name_exceeds_max_length(): void
    {
        $this->expectException(ValidationException::class);

        $this->action->create([
            'name' => str_repeat('a', 256),
            'email' => 'john@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);
    }

    public function test_password_is_hashed_and_not_stored_in_plaintext(): void
    {
        $user = $this->action->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $this->assertNotEquals('Password1!', $user->password);
        $this->assertTrue(Hash::check('Password1!', $user->password));
    }

    public function test_multiple_users_can_be_created_with_different_emails(): void
    {
        $user1 = $this->action->create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $user2 = $this->action->create([
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $this->assertNotEquals($user1->id, $user2->id);
        $this->assertDatabaseCount('users', 2);
    }
}
