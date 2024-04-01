<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewMediaObject extends Model
{
    use HasFactory;

     /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['gid', 'group', 'titl', 'obje_id', 'rin', 'created_at', 'updated_at'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
