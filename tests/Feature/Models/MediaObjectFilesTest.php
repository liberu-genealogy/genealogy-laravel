<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\MediaObject;
use App\Models\MediaObjeectFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * media_objects_file was created with only id/form/medi/timestamps, but
 * MediaObject::files() filters on `gid` + `group` (the GEDCOM gid/group
 * pseudo-key the vendor model declares fillable). So the relation — used by
 * the "Select GEDCOM Media" action on Edit Person — fatalled with
 * "no such column: gid". Guards that the relation resolves and matches on
 * gid + group='obje'.
 */
class MediaObjectFilesTest extends TestCase
{
    use RefreshDatabase;

    public function test_files_relation_returns_matching_gedcom_files(): void
    {
        $media = MediaObject::factory()->create();

        $match = MediaObjeectFile::create([
            'gid' => $media->id,
            'group' => 'obje',
            'form' => 'jpeg',
            'medi' => 'photo',
        ]);

        // Non-matching group must be excluded by the relation's ->where('group', 'obje').
        MediaObjeectFile::create([
            'gid' => $media->id,
            'group' => 'note',
            'form' => 'txt',
            'medi' => 'other',
        ]);

        $files = MediaObject::with('files')->find($media->id)->files;

        $this->assertCount(1, $files);
        $this->assertTrue($files->first()->is($match));
    }
}
