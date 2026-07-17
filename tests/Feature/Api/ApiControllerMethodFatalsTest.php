<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Jobs\DnaMatching;
use App\Models\Dna;
use App\Models\Family;
use App\Models\FamilyEvent;
use App\Models\Person;
use App\Models\PersonEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * These endpoints called methods that never existed — Family::familyEvents(),
 * Person::personEvents(), DnaImportService::queueImport() — so every one was a
 * guaranteed fatal on first hit. The existing API tests never exercised the
 * sub-resource routes or the DNA import, which is how they survived.
 */
class ApiControllerMethodFatalsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withPersonalTeam()->create();
        // Set current_team_id so BelongsToTenant scope works in API context
        $team = $this->user->ownedTeams()->first();
        if ($team) {
            $this->user->forceFill(['current_team_id' => $team->id])->save();
            $this->user->setRelation('currentTeam', $team);
        }
        Sanctum::actingAs($this->user);
    }

    public function test_person_events_endpoint_returns_the_persons_events(): void
    {
        $person = Person::factory()->create();
        $event = PersonEvent::factory()->create(['person_id' => $person->id]);

        $response = $this->getJson("/api/people/{$person->id}/events");

        // getAttribute(), not ->title: the vendor Event model declares a public
        // $title property that shadows Eloquent's __get, so ->title is always null.
        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['id' => $event->id, 'title' => $event->getAttribute('title')]);
    }

    public function test_person_events_endpoint_excludes_another_persons_events(): void
    {
        $person = Person::factory()->create();
        PersonEvent::factory()->create(['person_id' => Person::factory()->create()->id]);

        $this->getJson("/api/people/{$person->id}/events")
            ->assertOk()
            ->assertJsonCount(0);
    }

    public function test_family_events_endpoint_returns_the_familys_events(): void
    {
        $family = Family::factory()->create();
        $event = FamilyEvent::factory()->create(['family_id' => $family->id]);

        $response = $this->getJson("/api/families/{$family->id}/events");

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['id' => $event->id, 'title' => $event->getAttribute('title')]);
    }

    public function test_family_show_eager_loads_events_without_error(): void
    {
        $family = Family::factory()->create();
        $event = FamilyEvent::factory()->create(['family_id' => $family->id]);

        // show() load()s the same relation the events() route uses — the bad
        // name fataled here too, on a route the brief did not list.
        $this->getJson("/api/families/{$family->id}")
            ->assertOk()
            ->assertJsonFragment(['id' => $event->id]);
    }

    public function test_dna_import_endpoint_imports_the_uploaded_kit(): void
    {
        Storage::fake('private');
        Bus::fake();

        $response = $this->postJson('/api/import/dna', [
            'file' => UploadedFile::fake()->createWithContent('kit.txt', $this->kitContent()),
            'consent_given' => true,
        ]);

        $response->assertCreated()
            ->assertJsonStructure(['dna_id', 'variable_name', 'file_name', 'snp_count', 'format'])
            ->assertJsonFragment(['format' => '23andme']);

        $this->assertDatabaseHas('dnas', [
            'id' => $response->json('dna_id'),
            'user_id' => $this->user->id,
        ]);
        $this->assertTrue(Dna::find($response->json('dna_id'))->hasConsent());
    }

    public function test_dna_import_without_consent_does_not_dispatch_matching(): void
    {
        Storage::fake('private');
        Bus::fake();

        $response = $this->postJson('/api/import/dna', [
            'file' => UploadedFile::fake()->createWithContent('kit.txt', $this->kitContent()),
        ]);

        $response->assertCreated();
        $this->assertFalse(Dna::find($response->json('dna_id'))->hasConsent());
        Bus::assertNotDispatched(DnaMatching::class);
    }

    public function test_dna_import_rejects_a_file_that_is_not_a_dna_kit(): void
    {
        Storage::fake('private');

        $response = $this->postJson('/api/import/dna', [
            'file' => UploadedFile::fake()->createWithContent('notes.txt', str_repeat('not dna at all. ', 200)),
        ]);

        // Unparseable content clears the mimes rule, so this must be a 422, not a 500.
        $response->assertUnprocessable()->assertJsonStructure(['message']);
        $this->assertDatabaseCount('dnas', 0);
    }

    public function test_dna_import_requires_authentication(): void
    {
        $this->app['auth']->forgetGuards();

        $this->postJson('/api/import/dna', [
            'file' => UploadedFile::fake()->createWithContent('kit.txt', $this->kitContent()),
        ])->assertUnauthorized();
    }

    private function kitContent(): string
    {
        $lines = ['# This data file generated by 23andMe', "rsid\tchromosome\tposition\tgenotype"];
        for ($i = 0; $i < 600; $i++) {
            $pos = 1_000_000 + $i * 30_000;
            $lines[] = "rs{$i}\t1\t{$pos}\tAG";
        }

        return implode("\n", $lines)."\n";
    }
}
