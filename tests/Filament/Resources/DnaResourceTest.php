<?php

namespace Tests\Filament\Resources;

use App\Filament\Resources\DnaResource;
use App\Jobs\ImportGedcom;
use App\Models\Dna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DnaResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_configuration()
    {
        Storage::fake('private');

        Queue::fake();

        $user = User::factory()->create();

        $this->actingAs($user);

        $file = UploadedFile::fake()->create('document.ged', 100);

        $response = $this->post(route('filament.resources.dna.store'), [
            'attachment' => $file,
        ]);

        $response->assertStatus(302);

        Queue::assertPushed(ImportGedcom::class, function ($job) use ($user, $file) {
            return $job->user->is($user) && Storage::disk('private')->exists("gedcom-form-imports/{$file->hashName()}");
        });

        Storage::disk('private')->assertExists("gedcom-form-imports/{$file->hashName()}");
    }

    public function test_table_configuration()
    {
        $dna = Dna::factory()->create();

        $response = $this->get(route('filament.resources.dna.index'));

        $response->assertSeeText($dna->name)
                 ->assertSeeText($dna->user_id)
                 ->assertSeeText($dna->variable_name)
                 ->assertSeeText($dna->file_name)
                 ->assertSeeText($dna->created_at)
                 ->assertSeeText($dna->updated_at);
    }
}
