<?php

declare(strict_types=1);

namespace Tests\Filament\Widgets;

use App\Filament\App\Widgets\FamilyStatsWidget;
use App\Models\Family;
use App\Models\Person;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionMethod;
use Tests\TestCase;

class FamilyStatsWidgetTest extends TestCase
{
    use RefreshDatabase;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        // Auth + tenant first, so rows created in a test are stamped with this team and the
        // widget's team-scoped counts see them.
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam, isQuiet: true);
    }

    /** @return array<string, Stat> label => stat */
    private function stats(): array
    {
        $method = new ReflectionMethod(FamilyStatsWidget::class, 'getStats');
        $method->setAccessible(true);

        return collect($method->invoke(new FamilyStatsWidget))
            ->keyBy(fn (Stat $s): string => $s->getLabel())
            ->all();
    }

    public function test_stats_reflect_real_rows(): void
    {
        Person::factory()->count(2)->create(['deathday' => null]);
        Person::factory()->create(['deathday' => now()]);
        // Null parents: the Family factory otherwise auto-creates a husband + wife Person,
        // which would inflate the people counts.
        Family::factory()->create(['husband_id' => null, 'wife_id' => null]);

        $stats = $this->stats();

        $this->assertSame(3, $stats['Total People']->getValue());
        $this->assertSame(2, $stats['Living People']->getValue());
        $this->assertSame(1, $stats['Families']->getValue());
    }

    /** The fabricated sparkline arrays are gone — no stat carries a chart. */
    public function test_no_fabricated_sparklines(): void
    {
        foreach ($this->stats() as $stat) {
            $this->assertNull($stat->getChart());
        }
    }
}
