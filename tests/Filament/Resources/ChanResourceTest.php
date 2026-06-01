<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\ChanResource;
use App\Models\Chan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChanResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(Chan::class, ChanResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = ChanResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $chanData = [
            'group' => 'Test Group',
            'gid'   => 123,
            'date'  => '2023-01-01',
            'time'  => '12:00:00',
        ];

        // Create
        $chan = Chan::create($chanData);
        $this->assertDatabaseHas('chans', $chanData);

        // Read
        $retrievedChan = Chan::find($chan->id);
        $this->assertNotNull($retrievedChan);

        // Update
        $chan->update(['group' => 'Updated Group']);
        $this->assertDatabaseHas('chans', ['group' => 'Updated Group']);

        // Delete
        $chan->delete();
        $this->assertDatabaseMissing('chans', ['id' => $chan->id]);
    }
}
