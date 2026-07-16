<?php

declare(strict_types=1);

namespace Tests\Filament\Resources;

use App\Enums\MediaType;
use App\Filament\App\Resources\MediaObjectResource\Pages\CreateMediaObject;
use App\Models\MediaObject;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MediaObjectResourceTest extends TestCase
{
    use RefreshDatabase;

    private function actingUser(): User
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam);

        return $user;
    }

    public function test_create_page_mounts(): void
    {
        $this->actingUser();

        Livewire::test(CreateMediaObject::class)->assertOk();
    }

    public function test_persists_media_type_and_file_path(): void
    {
        $this->actingUser();

        $media = MediaObject::create([
            'titl' => 'Birth Certificate',
            'media_type' => MediaType::CERTIFICATE,
            'file_path' => 'media-objects/birth.pdf',
        ]);

        $this->assertDatabaseHas('media_objects', [
            'id' => $media->id,
            'media_type' => 'certificate',
            'file_path' => 'media-objects/birth.pdf',
        ]);

        $this->assertSame(MediaType::CERTIFICATE, $media->fresh()->media_type);
    }
}
