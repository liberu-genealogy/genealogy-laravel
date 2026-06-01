<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\AddrResource;
use App\Models\Addr;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddrResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(Addr::class, AddrResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = AddrResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $addrData = [
            'adr1' => '123 Main St',
            'adr2' => 'Suite 100',
            'city' => 'Anytown',
            'stae' => 'CA',
            'post' => '12345',
            'ctry' => 'USA',
        ];

        // Create
        $addr = Addr::create($addrData);
        $this->assertDatabaseHas('addrs', $addrData);

        // Read
        $retrievedAddr = Addr::find($addr->id);
        $this->assertNotNull($retrievedAddr);

        // Update
        $updatedData = ['city' => 'Newtown'];
        $addr->update($updatedData);
        $this->assertDatabaseHas('addrs', array_merge($addrData, $updatedData));

        // Delete
        $addr->delete();
        $this->assertDatabaseMissing('addrs', ['id' => $addr->id]);
    }
}
