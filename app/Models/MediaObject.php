<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MediaObject extends \FamilyTree365\LaravelGedcom\Models\MediaObject
{
    use HasFactory;
    use BelongsToTenant;

    /**
     * Files associated with this media object (from media_objects_file table).
     */
    public function files()
    {
        return $this->hasMany(MediaObjeectFile::class, 'gid', 'id')->where('group', 'obje');
    }
}
