<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use FamilyTree365\LaravelGedcom\Observers\EventActionsObserver;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewFamilyEvent extends Model
{
    use HasFactory;

    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table = 'family_events';

    protected $fillable = [
        'family_id',
        'places_id',
        'date',
        'created_date',
        'title',
        'description',
        'year',
        'month',
        'day',
        'type',
        'plac',
        'phon',
        'caus',
        'age',
        'husb',
        'wife',
    ];

    public static function boot()
    {
        self::observe(new EventActionsObserver());
    }

    public function newfamily()
    {
        return $this->hasOne(NewFamily::class, 'id', 'family_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
