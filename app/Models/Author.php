<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Author extends Model
{
    use BelongsToTenant;
    use HasFactory;

    #[\Override]
    protected $fillable = ['description', 'is_active', 'name'];

    #[\Override]
    protected $attributes = ['is_active' => false];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    #[\Override]
    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
