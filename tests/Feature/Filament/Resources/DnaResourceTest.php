<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources;

use App\Filament\App\Resources\DnaResource;
use App\Models\Dna;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DnaResourceTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withPersonalTeam()->create();
    }

    public function test_resource_has_correct_model(): void
    {
        $this->assertSame(Dna::class, DnaResource::getModel());
    }

    public function test_resource_navigation_is_configured(): void
    {
        $this->assertNotEmpty(DnaResource::getNavigationLabel());
    }

    public function test_resource_has_pages_defined(): void
    {
        $pages = DnaResource::getPages();

        $this->assertArrayHasKey('index', $pages);
    }

    public function test_dna_can_be_created_in_database(): void
    {
        $dna = Dna::factory()->create();

        $this->assertDatabaseHas('dnas', ['id' => $dna->id]);
    }
}
