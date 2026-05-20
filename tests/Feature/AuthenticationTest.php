<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        // In this Filament-based app, /login redirects to the Filament app panel
        $response->assertRedirect('/app/login');
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->assertAuthenticated();
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_get_default_tenant_returns_null_when_user_has_no_teams(): void
    {
        $user = User::factory()->create();

        $panel = Filament::getPanel('app');

        $this->assertNull($user->getDefaultTenant($panel));
    }

    public function test_get_default_tenant_falls_back_to_first_owned_team_when_current_team_id_is_null(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $team = $user->ownedTeams()->first();

        // Simulate a user with no current_team_id (e.g. created manually or by an old seeder)
        $user->forceFill(['current_team_id' => null])->save();
        $user->unsetRelation('latestTeam'); // clear any cached relationship value

        $panel = Filament::getPanel('app');

        $defaultTenant = $user->getDefaultTenant($panel);

        $this->assertNotNull($defaultTenant);
        $this->assertEquals($team->id, $defaultTenant->id);
    }

    public function test_get_default_tenant_returns_current_team_when_set(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $team = $user->ownedTeams()->first();

        $panel = Filament::getPanel('app');

        $defaultTenant = $user->getDefaultTenant($panel);

        $this->assertNotNull($defaultTenant);
        $this->assertEquals($team->id, $defaultTenant->id);
    }
}
