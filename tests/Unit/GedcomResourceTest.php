<?php

namespace Tests\Unit;

use App\Filament\App\Resources\GedcomResource;
use App\Jobs\ExportGedCom;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GedcomResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
    }

    public function testExportGedcomDispatchesJobWithAuthenticatedUser(): void
    {
        $user = User::factory()->create();
        Auth::login($user);

        GedcomResource::exportGedcom();

        Queue::assertPushed(ExportGedCom::class, fn($job): bool => $job->user->id === $user->id);
    }

    public function testExportGedcomFailsWithoutAuthenticatedUser(): void
    {
        Auth::logout();

        GedcomResource::exportGedcom();

        Queue::assertNotPushed(ExportGedCom::class);
    }
}
