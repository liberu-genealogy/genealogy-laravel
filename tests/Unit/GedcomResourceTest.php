<?php

namespace Tests\Unit;

use App\Filament\Resources\GedcomResource;
use App\Jobs\ExportGedCom;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class GedcomResourceTest extends TestCase
{
    #[\Override]
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
