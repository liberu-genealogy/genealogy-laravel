<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\DnaResource;
use App\Models\Dna;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
