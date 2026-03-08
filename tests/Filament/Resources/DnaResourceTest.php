<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\DnaResource;
use App\Models\Dna;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DnaResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_pages_registered(): void
    {
        $pages = DnaResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_table_configuration(): void
    {
        $dna = Dna::factory()->create();

        $this->assertDatabaseHas('dnas', [
            'id'            => $dna->id,
            'name'          => $dna->name,
            'variable_name' => $dna->variable_name,
        ]);
    }

    public function test_model_class_is_dna(): void
    {
        $this->assertEquals(\App\Models\Dna::class, DnaResource::getModel());
    }

    public function test_can_create_returns_true_for_authenticated_user_within_upload_limit(): void
    {
        $user = User::factory()->create(['dna_uploads_count' => 0]);
        Auth::login($user);

        $this->assertTrue(DnaResource::canCreate());
    }

    public function test_can_create_returns_false_when_upload_limit_reached(): void
    {
        $user = User::factory()->create(['dna_uploads_count' => 1, 'is_premium' => false]);
        Auth::login($user);

        $this->assertFalse(DnaResource::canCreate());
    }

    public function test_can_create_returns_false_when_unauthenticated(): void
    {
        Auth::logout();

        $this->assertFalse(DnaResource::canCreate());
    }
}
