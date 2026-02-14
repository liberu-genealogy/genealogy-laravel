<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaceEncoding extends Model
{
    use HasFactory;
    use BelongsToTenant;

    protected $fillable = [
        'person_id',
        'team_id',
        'source_photo_id',
        'encoding',
        'provider',
    ];

    protected $casts = [
        'encoding' => 'encrypted',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function sourcePhoto(): BelongsTo
    {
        return $this->belongsTo(PersonPhoto::class, 'source_photo_id');
    }
}
