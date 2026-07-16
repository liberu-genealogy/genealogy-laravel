<?php

namespace Tests\Unit;

use App\Filament\App\Resources\GedcomResource;
use App\Jobs\ExportGedCom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class GedcomResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
    }

    public function test_export_gedcom_dispatches_job_with_authenticated_user(): void
    {
        $user = User::factory()->create();
        Auth::login($user);

        GedcomResource::exportGedcom();

        Queue::assertPushed(ExportGedCom::class, fn ($job): bool => $job->user->id === $user->id);
    }

    public function test_export_gedcom_fails_without_authenticated_user(): void
    {
        Auth::logout();

        GedcomResource::exportGedcom();

        Queue::assertNotPushed(ExportGedCom::class);
    }
}
