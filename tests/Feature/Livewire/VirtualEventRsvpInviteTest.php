<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\VirtualEventRsvp;
use App\Models\Person;
use App\Models\Team;
use App\Models\User;
use App\Models\VirtualEvent;
use App\Models\VirtualEventAttendee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * RSVPing to an event is self-service — any member says whether they are
 * coming. Inviting others is an organiser action, and the two were not
 * distinguished: creating an attendee row for someone else ran on nothing more
 * than being logged in, so any member could invite arbitrary people to any
 * event in their team.
 *
 * The organiser rule already existed as canInvite (the event's creator, host or
 * moderator) but only drove which buttons rendered. It now gates the writes.
 * That is a per-event rule rather than a team-wide collaboration tier — a host
 * of one event is not thereby an organiser of another — which is the finer, and
 * already-modelled, of the two.
 */
class VirtualEventRsvpInviteTest extends TestCase
{
    use RefreshDatabase;

    private Team $team;

    private VirtualEvent $event;

    protected function setUp(): void
    {
        parent::setUp();

        $this->team = Team::factory()->create();
        $creator = User::factory()->create(['current_team_id' => $this->team->id]);
        $this->team->users()->attach($creator, ['role' => 'editor']);

        // Created while authenticated as the creator so the tenant trait stamps
        // team_id (NOT NULL), as the real create path does. The tests below
        // re-authenticate as their own actors.
        $this->actingAs($creator);
        $this->event = VirtualEvent::create([
            'title' => 'Family Reunion',
            // Published and future, so canUserRsvp() lets a member respond —
            // otherwise the self-service test would fail on event state, not on
            // anything this change touches.
            'status' => 'published',
            'start_time' => now()->addWeek(),
            'end_time' => now()->addWeek()->addHours(2),
            'created_by' => $creator->id,
        ]);
    }

    public function test_a_plain_member_cannot_invite_a_person(): void
    {
        $member = $this->member();

        Livewire::actingAs($member)
            ->test(VirtualEventRsvp::class, ['event' => $this->event])
            ->set('invite_person_id', $this->personWithEmail()->id)
            ->call('invitePerson')
            ->assertForbidden();

        $this->assertSame(0, $this->event->attendees()->count(), 'A plain member created an invitation.');
    }

    public function test_a_plain_member_cannot_send_invitations(): void
    {
        $member = $this->member();

        Livewire::actingAs($member)
            ->test(VirtualEventRsvp::class, ['event' => $this->event])
            ->set('invite_emails', 'someone@example.com')
            ->call('sendInvitations')
            ->assertForbidden();

        $this->assertSame(0, $this->event->attendees()->count());
    }

    public function test_a_moderator_can_invite(): void
    {
        $moderator = $this->member();
        VirtualEventAttendee::create([
            'virtual_event_id' => $this->event->id,
            'user_id' => $moderator->id,
            'rsvp_status' => 'accepted',
            'is_moderator' => true,
        ]);

        Livewire::actingAs($moderator)
            ->test(VirtualEventRsvp::class, ['event' => $this->event])
            ->set('invite_person_id', $this->personWithEmail()->id)
            ->call('invitePerson')
            ->assertOk();

        $this->assertSame(1, $this->event->attendees()->where('person_id', '!=', null)->count(), 'A moderator could not invite.');
    }

    /**
     * RSVPing is self-service and must stay open to any member — the guard is on
     * inviting, not on responding.
     */
    public function test_a_plain_member_can_still_rsvp_for_themselves(): void
    {
        $member = $this->member();

        Livewire::actingAs($member)
            ->test(VirtualEventRsvp::class, ['event' => $this->event])
            ->set('rsvp_status', 'accepted')
            ->call('submitRsvp')
            ->assertOk();

        $this->assertSame(
            1,
            $this->event->attendees()->where('user_id', $member->id)->count(),
            'A member could not RSVP for themselves.',
        );
    }

    /**
     * The duplicate-invite check scoped attendees with
     * ->where('guest_email', $email)->orWhereHas('user', ...). The unwrapped OR
     * broke out of the event's attendees() constraint, so a user attending a
     * DIFFERENT event under that email was treated as already invited here.
     */
    public function test_send_invitations_ignores_an_attendee_of_another_event(): void
    {
        $creator = $this->event->creator;

        // A user attends a *different* event under the email we're inviting here.
        $this->actingAs($creator);
        $otherEvent = VirtualEvent::create([
            'title' => 'Other Event',
            'status' => 'published',
            'start_time' => now()->addWeek(),
            'end_time' => now()->addWeek()->addHours(2),
            'created_by' => $creator->id,
        ]);
        $otherUser = User::factory()->create(['email' => 'invitee@example.com']);
        VirtualEventAttendee::create([
            'virtual_event_id' => $otherEvent->id,
            'user_id' => $otherUser->id,
            'rsvp_status' => 'accepted',
        ]);

        Livewire::actingAs($creator)
            ->test(VirtualEventRsvp::class, ['event' => $this->event])
            ->set('invite_emails', ['invitee@example.com'])
            ->call('sendInvitations')
            ->assertOk();

        $this->assertDatabaseHas('virtual_event_attendees', [
            'virtual_event_id' => $this->event->id,
            'guest_email' => 'invitee@example.com',
        ]);
    }

    private function member(): User
    {
        $user = User::factory()->create(['current_team_id' => $this->team->id]);
        $this->team->users()->attach($user, ['role' => 'editor']);

        return $user;
    }

    private function personWithEmail(): Person
    {
        return Person::factory()->create([
            'team_id' => $this->team->id,
            'email' => 'invitee@example.com',
        ]);
    }
}
