<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Jobs\DnaMatching;
use App\Models\Dna;
use App\Models\DnaMatching as DnaMatch;
use App\Models\User;
use App\Services\RecordMatcher\RecordMatcherService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

/**
 * A trivial job that records the permission team it saw when it ran, so a test
 * can prove the boundary reset fired before it.
 */
class TeamProbeJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public static int|string|null $seenTeamId = 'unset';

    public function handle(): void
    {
        self::$seenTeamId = app(PermissionRegistrar::class)->getPermissionsTeamId();
    }
}

/**
 * The analytical jobs read across every team on purpose — that is what matching
 * and deduplication are — so they cannot run scoped to one team. Instead each
 * row they write is stamped with the team it belongs to, taken from the owning
 * entity. In a worker with no authenticated user the tenant hook would leave
 * that team null, so the stamp is explicit.
 *
 * These run the code the way a worker does: unauthenticated.
 */
class BackgroundJobTeamStampingTest extends TestCase
{
    use RefreshDatabase;

    public function test_dna_matching_stamps_each_row_with_its_owners_team(): void
    {
        Storage::fake('private');
        $kit = $this->buildKit();
        Storage::disk('private')->put('kit_a.txt', $kit);
        Storage::disk('private')->put('kit_b.txt', $kit);

        $userA = User::factory()->withPersonalTeam()->create();
        $userB = User::factory()->withPersonalTeam()->create();

        $this->kit($userA, 'kit_a', 'kit_a.txt');
        $this->kit($userB, 'kit_b', 'kit_b.txt');

        // As a worker: no authenticated user, no tenant.
        Auth::logout();
        (new DnaMatching($userA, 'kit_a', 'kit_a.txt'))->handle();

        // The primary record belongs to A; the reciprocal, to B.
        $primary = DnaMatch::withoutGlobalScopes()->where('user_id', $userA->id)->first();
        $reciprocal = DnaMatch::withoutGlobalScopes()->where('user_id', $userB->id)->first();

        $this->assertNotNull($primary, 'No primary match row was written.');
        $this->assertSame($userA->current_team_id, $primary->team_id, 'The primary row did not carry the owner\'s team.');

        $this->assertNotNull($reciprocal, 'No reciprocal match row was written.');
        $this->assertSame(
            $userB->current_team_id,
            $reciprocal->team_id,
            'The reciprocal row carried the wrong team — it belongs to the matched kit\'s owner.',
        );
    }

    public function test_a_record_match_suggestion_carries_the_persons_team(): void
    {
        $team = User::factory()->withPersonalTeam()->create()->current_team_id;

        $suggestion = app(RecordMatcherService::class)->persistSuggestion(
            localPersonId: 1,
            provider: 'test',
            candidate: ['id' => 'ext-1', 'name' => 'A Match'],
            confidence: 0.9,
            teamId: $team,
        );

        $this->assertSame($team, $suggestion->team_id, 'A record-match suggestion was not stamped with its team.');
    }

    public function test_a_job_starts_with_a_clean_permission_team(): void
    {
        // A previous job in this worker left its team set.
        app(PermissionRegistrar::class)->setPermissionsTeamId(999999);

        // The next job runs. On the sync queue, dispatching fires the same
        // JobProcessing event a worker fires, so the Queue::before listener runs
        // before the job body — and the job records the team it saw.
        TeamProbeJob::$seenTeamId = 'unset';
        dispatch(new TeamProbeJob);

        $this->assertNull(
            TeamProbeJob::$seenTeamId,
            'A job ran with the previous job\'s permission team still set.',
        );
    }

    /**
     * On the sync queue a job runs inside the request that dispatched it, so the
     * request's permission team must survive the job — the reset gives the job a
     * clean start but must not leave the surrounding request with no team.
     */
    public function test_the_dispatching_requests_permission_team_survives_the_job(): void
    {
        $requestTeam = 4242;
        app(PermissionRegistrar::class)->setPermissionsTeamId($requestTeam);

        dispatch(new TeamProbeJob);

        $this->assertSame(
            $requestTeam,
            app(PermissionRegistrar::class)->getPermissionsTeamId(),
            'The job cleared the dispatching request\'s permission team and never restored it.',
        );
    }

    private function kit(User $user, string $varName, string $fileName): Dna
    {
        return Dna::create([
            'name' => $varName,
            'variable_name' => $varName,
            'file_name' => $fileName,
            'user_id' => $user->id,
            'consent_given' => true,
            'consent_given_at' => now(),
        ]);
    }

    private function buildKit(): string
    {
        $lines = [
            '# This data file generated by 23andMe',
            "rsid\tchromosome\tposition\tgenotype",
        ];

        for ($i = 0; $i < 600; $i++) {
            $pos = 1_000_000 + $i * 30_000;
            $lines[] = "rs{$i}\t1\t{$pos}\tAG";
        }

        return implode("\n", $lines)."\n";
    }
}
