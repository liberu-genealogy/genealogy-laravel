<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Filament\App\Resources\AIRecordMatchResource;
use App\Filament\App\Resources\ChecklistTemplateResource;
use App\Filament\App\Resources\DuplicateCheckResource;
use App\Filament\App\Resources\SmartMatchResource;
use App\Filament\App\Resources\VirtualEventResource;
use App\Models\AISuggestedMatch;
use App\Models\ChecklistTemplate;
use App\Models\Person;
use App\Models\SmartMatch;
use App\Models\User;
use App\Models\VirtualEvent;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

/**
 * Custom table actions with hand-written closures.
 *
 * Filament consults the resource's can* hooks for its own EditAction and
 * DeleteAction, but a bare Action::make(...)->action(fn () => ...) it just runs.
 * So the collaboration tier a resource enforces did not reach these closures,
 * and a viewer — who sees the table, because they hold read — could accept and
 * reject matches, run duplicate checks, provision meetings, duplicate templates.
 *
 * These assert the guard directly, not the button's visibility. An earlier
 * version tested visibility with assertTableActionHidden, and a review showed
 * that was vacuous: Filament's ->visible() is a rendering concern only —
 * mountAction checks isDisabled(), never isVisible() — so a crafted Livewire
 * request reaches a hidden action's body. The abort_unless inside is the only
 * real server-side guard, and removing it left every test green. The bodies are
 * now methods, called here as a viewer (must abort 403, no write) and as an
 * editor (must succeed), so the guard itself is what is under test.
 */
class ActionClosureTierEnforcementTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_viewer_cannot_review_a_smart_match(): void
    {
        [, $teamId, $userId] = $this->memberWithTier('viewer');
        $match = $this->smartMatch($teamId, $userId);

        $this->assertAborts(fn () => SmartMatchResource::reviewMatch($match, 'accepted'));
        $this->assertSame('pending', $match->fresh()->status, 'A viewer reviewed a match.');
    }

    public function test_an_editor_can_review_a_smart_match(): void
    {
        [, $teamId, $userId] = $this->memberWithTier('editor');
        $match = $this->smartMatch($teamId, $userId);

        SmartMatchResource::reviewMatch($match, 'accepted');

        $this->assertSame('accepted', $match->fresh()->status);
    }

    public function test_a_viewer_cannot_run_a_smart_search(): void
    {
        $this->memberWithTier('viewer');

        $this->assertAborts(fn () => SmartMatchResource::runMatchSearch());
    }

    public function test_a_viewer_cannot_review_an_ai_match(): void
    {
        [, $teamId] = $this->memberWithTier('viewer');
        $person = Person::factory()->create(['team_id' => $teamId]);
        $match = AISuggestedMatch::create([
            'team_id' => $teamId,
            'provider' => 'test',
            'external_record_id' => 'ext-1',
            'local_person_id' => $person->id,
            'status' => 'pending',
            'confidence' => 0.9,
        ]);

        $this->assertAborts(fn () => AIRecordMatchResource::reviewMatch($match, 'confirmed', 'x'));
        $this->assertSame('pending', $match->fresh()->status, 'A viewer confirmed an AI match.');
    }

    public function test_a_viewer_cannot_run_a_duplicate_check(): void
    {
        $this->memberWithTier('viewer');

        $this->assertAborts(fn () => DuplicateCheckResource::runCheck());
    }

    public function test_a_viewer_cannot_duplicate_a_template(): void
    {
        [, $teamId] = $this->memberWithTier('viewer');
        $template = ChecklistTemplate::create([
            'name' => 'Original',
            'team_id' => $teamId,
            'created_by' => $this->user->id,
        ]);

        $this->assertAborts(fn () => ChecklistTemplateResource::duplicateTemplate($template));
        $this->assertSame(1, ChecklistTemplate::count(), 'A viewer duplicated a template.');
    }

    public function test_an_editor_can_duplicate_a_template(): void
    {
        [, $teamId] = $this->memberWithTier('editor');
        $template = ChecklistTemplate::create([
            'name' => 'Original',
            'team_id' => $teamId,
            'created_by' => $this->user->id,
        ]);

        ChecklistTemplateResource::duplicateTemplate($template);

        $this->assertSame(2, ChecklistTemplate::count(), 'An editor could not duplicate a template.');
    }

    public function test_a_viewer_cannot_create_a_meeting(): void
    {
        [, $teamId] = $this->memberWithTier('viewer');
        $event = VirtualEvent::create([
            'team_id' => $teamId,
            'title' => 'Reunion',
            'start_time' => now()->addDay(),
            'end_time' => now()->addDay()->addHour(),
            'created_by' => $this->user->id,
        ]);

        $this->assertAborts(fn () => VirtualEventResource::createMeeting($event));
    }

    private User $user;

    /**
     * Asserts the callback aborts with 403 — the guard fired.
     */
    private function assertAborts(callable $callback): void
    {
        try {
            $callback();
            $this->fail('Expected a 403 abort, but the action was allowed.');
        } catch (HttpException $e) {
            $this->assertSame(403, $e->getStatusCode(), 'Aborted, but not with 403.');
        }
    }

    private function smartMatch(int $teamId, int $userId): SmartMatch
    {
        $person = Person::factory()->create(['team_id' => $teamId]);

        return SmartMatch::create([
            'team_id' => $teamId,
            'user_id' => $userId,
            'person_id' => $person->id,
            'match_source' => 'test',
            'match_data' => ['name' => 'A. Match'],
            'status' => 'pending',
        ]);
    }

    /**
     * @return array{0: User, 1: int, 2: int}
     */
    private function memberWithTier(string $tier): array
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $member = User::factory()->withPersonalTeam()->create();
        $owner->currentTeam->users()->attach($member, ['role' => $tier]);

        $member->forceFill(['current_team_id' => $owner->current_team_id])->save();
        $member = $member->fresh();

        $this->user = $member;
        $this->actingAs($member);
        Filament::setTenant($owner->currentTeam->fresh(), isQuiet: true);

        return [$member, $owner->current_team_id, $member->id];
    }
}
