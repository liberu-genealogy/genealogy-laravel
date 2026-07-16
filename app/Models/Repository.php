<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    use HasFactory;
    use BelongsToTenant;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    #[\Override]
    protected $keyType = 'integer';

    #[\Override]
    protected $fillable = ['repo', 'name', 'addr_id', 'rin', 'phon', 'email', 'fax', 'www', 'name', 'description', 'type_id', 'is_active'];

    #[\Override]
    protected $attributes = ['is_active' => false];

    public function sources()
    {
        return $this->hasMany(Source::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
    #[\Override]
    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
