<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use BelongsToTenant;
    use HasFactory;

    #[\Override]
    protected $fillable = ['name', 'description', 'is_active'];

    #[\Override]
    protected $attributes = ['is_active' => false];

    #[\Override]
    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
