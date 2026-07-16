<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Jobs\ImportGedcom;
use App\Models\User;
use App\Services\GedcomService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use ReflectionObject;
use Tests\TestCase;

/**
 * queueImport dispatched ImportGedcom with the wrong arguments — ($path, $slug)
 * against a (User, filePath, ?slug) constructor — so the relative path landed in
 * the User slot (TypeError) and every queued GEDCOM import died before running.
 */
class GedcomQueueImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_queue_import_dispatches_the_job_with_a_user_and_absolute_path(): void
    {
        Storage::fake('private');
        Bus::fake();

        $user = User::factory()->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->createWithContent('tree.ged', "0 HEAD\n0 TRLR\n");

        $job = (new GedcomService)->queueImport($file);

        $this->assertDatabaseHas('importjobs', ['slug' => $job->slug, 'user_id' => $user->id]);

        Bus::assertDispatched(ImportGedcom::class, function (ImportGedcom $dispatched) use ($user, $job): bool {
            $ref = new ReflectionObject($dispatched);

            $userProp = $ref->getProperty('user');
            $userProp->setAccessible(true);
            $filePathProp = $ref->getProperty('filePath');
            $filePathProp->setAccessible(true);

            $dispatchedUser = $userProp->getValue($dispatched);
            $filePath = $filePathProp->getValue($dispatched);

            // User is a real User (not the path string), the file path is an
            // absolute, existing file (not the relative store() path), slug matches.
            return $dispatchedUser instanceof User
                && $dispatchedUser->is($user)
                && is_string($filePath)
                && is_file($filePath)
                && $dispatched->slug === $job->slug;
        });
    }
}
