<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\DescendantChartComponent;
use App\Livewire\FanChart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * The fan and descendant charts loaded D3 from https://d3js.org, which the
 * global SecurityHeaders CSP script-src blocks — so both rendered a silent
 * blank box. They must serve the vendored copy from public/js instead.
 */
class ChartD3AssetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->withPersonalTeam()->create();
        $team = $user->ownedTeams()->first();
        if ($team) {
            $user->forceFill(['current_team_id' => $team->id])->save();
        }
        $this->actingAs($user);
    }

    public function test_vendored_d3_asset_is_present(): void
    {
        $this->assertFileExists(public_path('js/d3.v7.min.js'));
    }

    public function test_fan_chart_serves_local_d3_not_the_cdn(): void
    {
        Livewire::test(FanChart::class)
            ->assertSee('js/d3.v7.min.js')
            ->assertDontSee('d3js.org');
    }

    public function test_descendant_chart_serves_local_d3_not_the_cdn(): void
    {
        Livewire::test(DescendantChartComponent::class)
            ->assertSee('js/d3.v7.min.js')
            ->assertDontSee('d3js.org');
    }
}
