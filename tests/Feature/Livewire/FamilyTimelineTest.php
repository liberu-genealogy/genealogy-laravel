<?php

namespace Tests\Feature\Livewire;

use App\Livewire\TimelineComponent;
use App\Models\Family;
use App\Models\FamilyEvent;
use App\Models\Person;
use App\Models\PersonEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FamilyTimelineTest extends TestCase
{
    use RefreshDatabase;

    public function test_family_timeline_merges_member_and_family_events(): void
    {
        $husband = Person::factory()->create();
        $wife = Person::factory()->create();

        $family = Family::factory()->create([
            'husband_id' => $husband->id,
            'wife_id' => $wife->id,
        ]);

        // A member's own person-event and the family's own event.
        $personEvent = PersonEvent::factory()->create([
            'person_id' => $husband->id,
            'title' => 'Husband Baptism',
            'date' => '1901-05-01',
        ]);

        $familyEvent = FamilyEvent::factory()->create([
            'family_id' => $family->id,
            'title' => 'Wedding',
            'date' => '1925-06-15',
        ]);

        $ids = array_column(
            Livewire::test(TimelineComponent::class, ['familyId' => $family->id])->get('events'),
            'id'
        );

        // Merged timeline carries the member's person-event AND the family event.
        $this->assertContains('p_'.$personEvent->id, $ids);
        $this->assertContains('f_'.$familyEvent->id, $ids);
    }
}
