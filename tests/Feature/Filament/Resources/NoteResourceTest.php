<?php

namespace Tests\Feature\Filament\Resources;

use App\Filament\App\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_has_correct_model(): void
    {
        $this->assertEquals(Note::class, NoteResource::getModel());
    }

    public function test_resource_navigation_is_configured(): void
    {
        $this->assertNotEmpty(NoteResource::getNavigationLabel());
    }

    public function test_note_can_be_created_in_database(): void
    {
        $note = Note::factory()->create();

        $this->assertDatabaseHas('notes', ['id' => $note->id]);
    }
}
