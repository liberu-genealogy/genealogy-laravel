<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Models\Person;
use App\Models\User;
use App\Services\DuplicateDetectionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * The duplicate scanner must never pair one team's person with another's.
 *
 * It runs as a scheduled job with no authenticated user, so the tenant scope is
 * inactive and it loads every team's people together — correct for one global
 * pass. But it then compared every person against every other regardless of
 * team, so two people with the same name on different teams became a
 * DuplicateMatch. That surfaces one team's record inside another's dedupe
 * queue: a cross-tenant disclosure, not just an unstamped row.
 *
 * The scan is invoked here exactly as the job does — unauthenticated — so the
 * isolation comes from the code, not from a tenant the test happens to set.
 */
class DuplicateScanIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_people_on_different_teams_are_never_matched(): void
    {
        $teamA = $this->teamId();
        $teamB = $this->teamId();

        // The same distinctive name on two teams — an exact-ish match the fuzzy
        // pass would pair if it ignored the team.
        $this->person($teamA, 'Archibald', 'Fitzgerald');
        $this->person($teamB, 'Archibald', 'Fitzgerald');

        Auth::logout();
        $matches = app(DuplicateDetectionService::class)->scan(0.5);

        $this->assertCount(0, $matches, 'The scanner paired people across two teams.');
    }

    public function test_people_on_the_same_team_are_still_matched(): void
    {
        $team = $this->teamId();

        $this->person($team, 'Archibald', 'Fitzgerald');
        $this->person($team, 'Archibald', 'Fitzgerald');

        Auth::logout();
        $matches = app(DuplicateDetectionService::class)->scan(0.5);

        $this->assertGreaterThan(0, $matches->count(), 'The scanner missed a real same-team duplicate.');
        $this->assertSame(
            $team,
            (int) $matches->first()->team_id,
            'A duplicate match was not stamped with its team.',
        );
    }

    /**
     * The exact-phone path scores 0.93 and has its own guard, separate from the
     * fuzzy-name loop. It is the highest-scoring reachable contact match: email
     * is a globally unique column, so two people can never share one and that
     * path is unreachable, but phone is not unique. Distinct names here so the
     * fuzzy path scores below threshold — the two people are the "same" only by
     * phone, which is exactly what this guard must catch across teams.
     */
    public function test_the_same_phone_on_different_teams_is_never_matched(): void
    {
        $teamA = $this->teamId();
        $teamB = $this->teamId();

        $this->person($teamA, 'Bartholomew', 'Aardvark', phone: '+1-555-0100');
        $this->person($teamB, 'Cornelius', 'Zylberschlag', phone: '+1-555-0100');

        Auth::logout();
        $matches = app(DuplicateDetectionService::class)->scan(0.5);

        $this->assertCount(0, $matches, 'The scanner paired people across teams by phone.');
    }

    public function test_the_same_phone_on_the_same_team_is_still_matched(): void
    {
        $team = $this->teamId();

        // Names differ, so only the phone path can pair them — proving that path
        // still works within a team, not just that fuzzy names do.
        $this->person($team, 'Bartholomew', 'Aardvark', phone: '+1-555-0100');
        $this->person($team, 'Cornelius', 'Zylberschlag', phone: '+1-555-0100');

        Auth::logout();
        $matches = app(DuplicateDetectionService::class)->scan(0.5);

        $this->assertGreaterThan(0, $matches->count(), 'The scanner missed a same-team phone duplicate.');
    }

    private function teamId(): int
    {
        return User::factory()->withPersonalTeam()->create()->current_team_id;
    }

    private function person(int $teamId, string $givn, string $surn, ?string $email = null, ?string $phone = null): Person
    {
        // Stamped directly: created without auth here, as the scan itself runs.
        $person = Person::factory()->create([
            'givn' => $givn,
            'surn' => $surn,
            'name' => "{$givn} {$surn}",
            'email' => $email,
            'phone' => $phone,
        ]);
        $person->forceFill(['team_id' => $teamId])->save();

        return $person;
    }
}
