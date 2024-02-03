<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LaravelLiberu\Tables\Traits\TableCache;

class Tree extends Model
{
    use TableCache, HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['name', 'description'];
}
