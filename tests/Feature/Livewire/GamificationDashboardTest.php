<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\GamificationDashboard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GamificationDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_component_renders(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        Livewire::test(GamificationDashboard::class)
            ->assertOk();
    }

    public function test_can_switch_active_tab(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        Livewire::test(GamificationDashboard::class)
            ->set('activeTab', 'achievements')
            ->assertSet('activeTab', 'achievements');
    }

    public function test_can_switch_leaderboard_period(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        Livewire::test(GamificationDashboard::class)
            ->set('leaderboardPeriod', 'monthly')
            ->assertSet('leaderboardPeriod', 'monthly');
    }

    public function test_can_filter_by_achievement_category(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        Livewire::test(GamificationDashboard::class)
            ->set('achievementCategory', 'genealogy')
            ->assertSet('achievementCategory', 'genealogy');
    }

    public function test_can_toggle_show_only_unlocked(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        Livewire::test(GamificationDashboard::class)
            ->set('showOnlyUnlocked', true)
            ->assertSet('showOnlyUnlocked', true);
    }
}
