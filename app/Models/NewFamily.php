<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class NewFamily extends Model
{
    use HasFactory;

    public $husband;
    public $wife;
    public $id;
    protected $fillable = ['description', 'is_active', 'type_id', 'husband_id', 'wife_id', 'chan', 'nchi', 'rin'];

    protected $attributes = ['is_active' => false];

    protected $casts = ['is_active' => 'boolean'];

    public function events()
    {
        return $this->hasMany(FamilyEvent::class);
    }

    public function children()
    {
        return $this->hasMany(Person::class, 'child_in_family_id');
    }

    public function husband()
    {
        return $this->hasOne(Person::class, 'id', 'husband_id');
    }

    public function wife()
    {
        return $this->hasOne(Person::class, 'id', 'wife_id');
    }

    public function title()
    {
        return ($this->husband ? $this->husband->fullname() : '?').
            ' + '.
            ($this->wife ? $this->wife->fullname() : '?');
    }

    public static function getList()
    {
        $families = self::query()->get();
        $result = [];
        foreach ($families as $family) {
            $result[$family->id] = $family->title();
        }

        return collect($result);
    }

    public function addEvent($title, $date, $place, $description = '')
    {
        $place_id = Place::getIdByTitle($place);
        $event = FamilyEvent::query()->updateOrCreate(
            [
                'family_id' => $this->id,
                'title'     => $title,
            ],
            [
                'family_id'   => $this->id,
                'title'       => $title,
                'description' => $description,
            ]
        );
        if ($date) {
            $event->date = $date;
            $event->save();
        }
        if ($place) {
            $event->places_id = $place_id;
            $event->save();
        }
    }

    public function getWifeName()
    {
        return $this->wife ? $this->wife->fullname() : 'unknown woman';
    }

    public function getHusbandName()
    {
        return $this->husband ? $this->husband->fullname() : 'unknown man';
    }


    // public function husband()
    // {
    //     return $this->belongsTo(Person::class, 'husband_id');
    // }

    // public function wife()
    // {
    //     return $this->belongsTo(Person::class, 'wife_id');
    // }

    // /**
    //  * Get the children of the family.
    //  *
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function children()
    // {
    //     return $this->hasMany(Person::class, 'child_in_family_id');
    // }



    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

}
