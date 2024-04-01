<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewRepository extends Model
{
    use HasFactory;

       /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    protected $fillable = ['repo', 'name', 'addr_id', 'rin', 'phon', 'email', 'fax', 'www', 'name', 'description', 'type_id', 'is_active'];

    protected $attributes = ['is_active' => false];

    protected $casts = ['is_active' => 'boolean'];

    public function Newsources()
    {
        return $this->hasMany(New_Source::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }


}
