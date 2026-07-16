<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MediaType;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MediaObject extends \FamilyTree365\LaravelGedcom\Models\MediaObject
{
    use BelongsToTenant;
    use HasFactory;

    /**
     * The vendor base hardcodes $fillable. Append our columns to whatever it
     * declares (rather than redeclaring the full list) so vendor changes carry
     * through instead of being silently masked.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->mergeFillable(['media_type', 'file_path']);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'media_type' => MediaType::class,
        ];
    }

    /**
     * Files associated with this media object (from media_objects_file table).
     */
    public function files()
    {
        return $this->hasMany(MediaObjeectFile::class, 'gid', 'id')->where('group', 'obje');
    }
}
