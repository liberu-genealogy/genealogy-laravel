<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Author extends Model
{
    use HasFactory;
    use BelongsToTenant;

    protected $fillable = ['description', 'is_active', 'name'];

    protected $attributes = ['is_active' => false];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
