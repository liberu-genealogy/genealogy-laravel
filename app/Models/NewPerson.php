<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewPerson extends Model
{
    use HasFactory;

    protected $fillable = [
        'gid',
        'givn',
        'surn',
        'sex',
        'child_in_family_id',
        'description',
        'titl',
        'name',
        'appellative',
        'email',
        'phone',
        'birthday',
        'deathday',
        'burial_day',
        'bank',
        'bank_account',
        'chan',
        'rin',
        'resn',
        'rfn',
        'afn',
    ];
    // public function searchableAs()
    // {
    //     return 'name';
    // }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $casts = [
        'deleted_at' => 'datetime',
        'birthday'   => 'datetime',
        'deathday'   => 'datetime',
        'burial_day' => 'datetime',
        'chan'       => 'datetime',
    ];

    protected $guarded = ['id'];

//    protected $fillable = [
//        'gid',
//        'givn',
//        'surn',
//        'sex',
//        'child_in_family_id',
//        'description',
//        'title', 'name', 'appellative', 'uid', 'email', 'phone', 'birthday',
//        'deathday', 'burial_day', 'bank', 'bank_account',
//        'uid', 'chan', 'rin', 'resn', 'rfn', 'afn',
//    ];

//     public function __construct(array $attributes = [])
//     {
//         parent::__construct($attributes);
//         // $this->setConnection(\Session::get('conn'));
//    //     error_log('Person-'.($this->connection).'-'.\Session::get('conn').'-'.\Session::get('db'));
//     }

    public function events()
    {
        return $this->hasMany(PersonEvent::class);
    }

    public function childInFamily()
    {
        return $this->belongsTo(Family::class, 'child_in_family_id');
    }

    public function familiesAsHusband()
    {
        return $this->hasMany(Family::class, 'husband_id');
    }

    public function familiesAsWife()
    {
        return $this->hasMany(Family::class, 'wife_id');
    }

    public function parents()
    {
        return $this->childInFamily->parents();
    }

    public function children()
    {
        return $this->hasManyThrough(Person::class, Family::class, 'husband_id', 'child_in_family_id')->union($this->hasManyThrough(Person::class, Family::class, 'wife_id', 'child_in_family_id'));
    }

    public function fullname(): string
    {
        return $this->givn.' '.$this->surn;
    }

    public function getSex(): string
    {
        if ($this->sex === 'F') {
            return 'Female';
        }

        return 'Male';
    }

    public static function getList()
    {
        $persons = self::get();
        $result = [];
        foreach ($persons as $person) {
            $result[$person->id] = $person->fullname();
        }

        return collect($result);
    }

    public function addEvent($title, $date, $place, $description = '')
    {
        $place_id = Place::getIdByTitle($place);
        $event = PersonEvent::updateOrCreate(
            [
                'person_id' => $this->id,
                'title'     => $title,
            ],
            [
                'person_id'   => $this->id,
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

        // add birthyear to person table ( for form builder )
        if ($title === 'BIRT' && !empty($date)) {
            $this->birthday = date('Y-m-d', strtotime((string) $date));
        }
        // add deathyear to person table ( for form builder )
        if ($title === 'DEAT' && !empty($date)) {
            $this->deathday = date('Y-m-d', strtotime((string) $date));
        }
        $this->save();

        return $event;
    }

    public function birth()
    {
        return $this->events->where('title', '=', 'BIRT')->first();
    }

    public function death()
    {
        return $this->events->where('title', '=', 'DEAT')->first();
    }


    
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

}
