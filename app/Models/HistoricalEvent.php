<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\Month;
use Carbon\WeekDay;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class HistoricalEvent extends Model
{
    use HasFactory;

    #[\Override]
    protected $table = 'historical_events';

    #[\Override]
    protected $fillable = [
        'title',
        'description',
        'date',
        'year',
        'month',
        'day',
        'place',
        'country',
        'latitude',
        'longitude',
        'source_url',
    ];

    #[\Override]
    protected $casts = [
        'date' => 'date',
        'latitude' => 'decimal:6',
        'longitude' => 'decimal:6',
    ];

    /**
     * Scope events between two dates (inclusive).
     */
    public function scopeBetweenDates($query, $start, $end)
    {
        return $query->whereBetween('date', [$start, $end]);
    }

    public static function yearFromDate(\DateTimeInterface|WeekDay|Month|string|int|float|null $date)
    {
        try {
            return Carbon::parse($date)->year;
        } catch (Throwable) {
            return null;
        }
    }
}
