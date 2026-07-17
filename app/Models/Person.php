<?php

namespace App\Models;

// use App\Traits\ConnectionTrait;

use App\Enums\PedigreeType;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

// use Laravel\Scout\Searchable;
// use LaravelLiberu\People\Models\Person as CorePerson;

class Person extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use SoftDeletes;

    // Gender constants
    public const GENDER_MALE = 'M';

    public const GENDER_FEMALE = 'F';

    public const GENDER_UNKNOWN = 'U';

    /**
     * Single source of truth for sex code => label. Add codes here (e.g. 'X' => 'Unspecified')
     * to extend; forms, filters and getSex() all read from this map.
     */
    public const SEX_OPTIONS = [
        self::GENDER_MALE => 'Male',
        self::GENDER_FEMALE => 'Female',
        self::GENDER_UNKNOWN => 'Unknown',
    ];

    #[\Override]
    protected $fillable = [
        'gid',
        'givn',
        'surn',
        'sex',
        'child_in_family_id',
        'pedigree',
        'description',
        'titl',
        'name',
        'appellative',
        'uid',
        'email',
        'phone',
        'photo_url',
        // Name components (GEDCOM)
        'npfx',
        'nick',
        'spfx',
        'nsfx',
        'type',
        // Birth
        'birthday',
        'birth_month',
        'birth_year',
        'birthday_dati',
        'birthday_plac',
        // Death
        'deathday',
        'death_month',
        'death_year',
        'deathday_dati',
        'deathday_plac',
        'deathday_caus',
        // Burial
        'burial_day',
        'burial_month',
        'burial_year',
        'burial_day_dati',
        'burial_day_plac',
        // Christening / family refs
        'chr',
        'famc',
        'fams',
        // Finance
        'bank',
        'bank_account',
        // GEDCOM metadata
        'chan',
        'rin',
        'resn',
        'rfn',
        'afn',
        // App-specific
        'tree_position_x',
        'tree_position_y',
        'team_id',
    ];

    #[\Override]
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
        return $this->hasMany(PersonEvent::class)->select(['id', 'person_id', 'title', 'date', 'places_id']);
    }

    /**
     * GEDCOM ASSO — links to people that no family record expresses: step-parents,
     * guardians, godparents, witnesses. Family membership covers birth/adoptive
     * parents (see PedigreeType); everything else lives here.
     *
     * `person_asso` is stringly-typed by GEDCOM convention: `group` names the kind
     * of record the row hangs off ('indi' = a person) and `gid` is that record's id.
     * withAttributes() both constrains reads to this group AND stamps it on create —
     * a bare where() would filter reads and silently write group = null.
     *
     * The associated person is `indi`, a varchar: the importer first writes the raw
     * GEDCOM xref ("@I5@") and a later pass resolves it to a person id, so rows with
     * import_confirm = 0 may not resolve to a Person at all.
     */
    public function associations(): HasMany
    {
        return $this->hasMany(PersonAsso::class, 'gid')
            ->withAttributes(['group' => PersonAsso::GROUP_INDI]);
    }

    /**
     * Associations pointing AT this person. GEDCOM records an ASSO in one direction
     * only, so a guardian has no row of their own — they are found by their ward's.
     * Read-only in practice: create from the subject's side via associations().
     */
    public function associatedWith(): HasMany
    {
        return $this->hasMany(PersonAsso::class, 'indi')
            ->withAttributes(['group' => PersonAsso::GROUP_INDI]);
    }

    /**
     * GEDCOM SOUR references — the sources evidencing this person, with the page,
     * quality and text of each citation. Same (group, gid) convention as above.
     *
     * Nothing wrote group = 'indi' before this: the importer only ever emits the
     * finer-grained 'indi_name'/'indi_even'/'indi_asso'/'indi_lds' groups, which is
     * why CompletenessService's person-source coverage always reported zero.
     */
    public function sourceRefs(): HasMany
    {
        return $this->hasMany(SourceRef::class, 'gid')
            ->withAttributes(['group' => SourceRef::GROUP_INDI]);
    }

    public function childInFamily()
    {
        return $this->belongsTo(Family::class, 'child_in_family_id')->select(['id', 'husband_id', 'wife_id']);
    }

    public function familiesAsHusband()
    {
        return $this->hasMany(Family::class, 'husband_id');
    }

    public function familiesAsWife()
    {
        return $this->hasMany(Family::class, 'wife_id');
    }

    public function families(): Collection
    {
        return $this->familiesAsHusband->merge($this->familiesAsWife);
    }

    public function parents()
    {
        if (! $this->childInFamily) {
            return collect();
        }

        $parents = collect();

        if ($this->childInFamily->husband) {
            $parents->push($this->childInFamily->husband);
        }

        if ($this->childInFamily->wife) {
            $parents->push($this->childInFamily->wife);
        }

        return $parents;
    }

    public function father()
    {
        return $this->childInFamily?->husband;
    }

    public function mother()
    {
        return $this->childInFamily?->wife;
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
        return self::SEX_OPTIONS[$this->sex] ?? 'Unknown';
    }

    /** GEDCOM FAMC.PEDI ADOPTED. Null pedigree = biological, so not adopted. */
    public function isAdopted(): bool
    {
        return $this->pedigree === PedigreeType::ADOPTED;
    }

    /** Human label for the child-family link type; null pedigree reads as biological. */
    public function pedigreeLabel(): string
    {
        return ($this->pedigree ?? PedigreeType::BIRTH)->label();
    }

    public static function getList(): Collection
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
                'title' => $title,
            ],
            [
                'person_id' => $this->id,
                'title' => $title,
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
        if ($title === 'BIRT' && ! empty($date)) {
            $this->birthday = date('Y-m-d', strtotime((string) $date));
        }
        // add deathyear to person table ( for form builder )
        if ($title === 'DEAT' && ! empty($date)) {
            $this->deathday = date('Y-m-d', strtotime((string) $date));
        }
        $this->save();

        return $event;
    }

    public function birth()
    {
        return $this->events()->where('title', '=', 'BIRT')->first();
    }

    public function death()
    {
        return $this->events()->where('title', '=', 'DEAT')->first();
    }

    public function scopeWithBasicInfo($query)
    {
        return $query->select(['id', 'givn', 'surn', 'sex', 'child_in_family_id', 'birthday', 'deathday']);
    }

    /**
     * Determine if this person is considered "living" (no death record and born within 100 years).
     */
    public function isLiving(): bool
    {
        if ($this->deathday) {
            return false;
        }

        $cutoff = now()->subYears(100);

        if ($this->birthday) {
            return $this->birthday->greaterThan($cutoff);
        }

        if ($this->birth_year) {
            return (int) $this->birth_year > $cutoff->year;
        }

        // No death and no known birth — assume potentially living for privacy
        return true;
    }

    /**
     * Scope to only deceased / historically safe persons (dead or born 100+ years ago).
     */
    public function scopeDeceased($query)
    {
        $cutoffYear = now()->subYears(100)->year;

        return $query->where(function ($q) use ($cutoffYear): void {
            $q->whereNotNull('deathday')
                ->orWhere(function ($q2) use ($cutoffYear): void {
                    $q2->whereNotNull('birth_year')
                        ->where('birth_year', '<=', $cutoffYear);
                })
                ->orWhere(function ($q2): void {
                    $q2->whereNotNull('birthday')
                        ->where('birthday', '<=', now()->subYears(100));
                });
        });
    }

    /**
     * Scope to living persons (no death record AND born within 100 years or unknown birth).
     */
    public function scopeLiving($query)
    {
        $cutoffYear = now()->subYears(100)->year;

        return $query->whereNull('deathday')
            ->where(function ($q) use ($cutoffYear): void {
                $q->where(function ($q2) use ($cutoffYear): void {
                    $q2->whereNotNull('birth_year')
                        ->where('birth_year', '>', $cutoffYear);
                })
                    ->orWhere(function ($q2): void {
                        $q2->whereNotNull('birthday')
                            ->where('birthday', '>', now()->subYears(100));
                    })
                    ->orWhere(function ($q2): void {
                        $q2->whereNull('birth_year')
                            ->whereNull('birthday');
                    });
            });
    }

    public static function getListOptimized()
    {
        return self::withBasicInfo()->get()->mapWithKeys(fn ($person): array => [$person->id => $person->fullname()]);
    }

    public static function getListCached()
    {
        return cache()->remember('person_list', now()->addHours(1), fn () => self::getListOptimized());
    }

    public static function getBasicInfoCached($id)
    {
        return cache()->remember("person_basic_info_{$id}", now()->addHours(1), fn () => self::withBasicInfo()->find($id));
    }

    /**
     * Return the best-guess profile image URL for a person.
     * Tries multiple non-destructive locations and returns a default when none found.
     */
    public function profileImageUrl(): string
    {
        // 1) Prefer an explicit attribute if present (e.g. photo_url or image)
        if (! empty($this->photo_url)) {
            return $this->photo_url;
        }
        if (! empty($this->image)) {
            return $this->image;
        }

        // 2) Look for files in the public storage under predictable paths
        try {
            $disk = Storage::disk('public');
            $candidates = [
                "people/{$this->id}.jpg",
                "people/{$this->id}.jpeg",
                "people/{$this->id}.png",
                "people/{$this->id}.webp",
                "photos/{$this->id}.jpg",
                "photos/{$this->id}.png",
            ];

            foreach ($candidates as $path) {
                if ($disk->exists($path)) {
                    return $disk->url($path);
                }
            }
        } catch (\Exception) {
            // ignore storage errors and continue to fallback
        }

        // 3) Fallback to a bundled public asset (keeps UI consistent)
        return asset('images/default-avatar.svg');
    }

    /**
     * Get photos associated with this person
     */
    public function photos()
    {
        return $this->hasMany(PersonPhoto::class);
    }

    /**
     * Get photo tags where this person is tagged
     */
    public function photoTags()
    {
        return $this->hasMany(PhotoTag::class);
    }

    /**
     * Get confirmed photo tags for this person
     */
    public function confirmedPhotoTags()
    {
        return $this->hasMany(PhotoTag::class)->where('status', 'confirmed');
    }

    /**
     * Get face encodings for this person
     */
    public function faceEncodings()
    {
        return $this->hasMany(FaceEncoding::class);
    }

    /**
     * Get checklists associated with this person
     */
    public function checklists()
    {
        return $this->morphMany(UserChecklist::class, 'subject');
    }

    /**
     * Get active checklists for this person
     */
    public function activeChecklists()
    {
        return $this->checklists()->whereIn('status', [
            UserChecklist::STATUS_NOT_STARTED,
            UserChecklist::STATUS_IN_PROGRESS,
        ]);
    }

    /**
     * Get completed checklists for this person
     */
    public function completedChecklists()
    {
        return $this->checklists()->where('status', UserChecklist::STATUS_COMPLETED);
    }

    /**
     * Get the total research progress for this person
     */
    public function getResearchProgressAttribute(): float
    {
        $totalChecklists = $this->checklists()->count();
        if ($totalChecklists === 0) {
            return 0;
        }

        $completedChecklists = $this->completedChecklists()->count();

        return round(($completedChecklists / $totalChecklists) * 100, 2);
    }

    /**
     * Check if this person has any overdue checklists
     */
    public function hasOverdueChecklists(): bool
    {
        return $this->checklists()
            ->where('due_date', '<', now())
            ->where('status', '!=', UserChecklist::STATUS_COMPLETED)
            ->exists();
    }

    /**
     * Get research summary for this person
     */
    public function getResearchSummary(): array
    {
        $checklists = $this->checklists()->with('items')->get();

        return [
            'total_checklists' => $checklists->count(),
            'completed_checklists' => $checklists->where('status', UserChecklist::STATUS_COMPLETED)->count(),
            'in_progress_checklists' => $checklists->where('status', UserChecklist::STATUS_IN_PROGRESS)->count(),
            'overdue_checklists' => $checklists->filter(fn ($c) => $c->is_overdue)->count(),
            'total_items' => $checklists->sum(fn ($c) => $c->items->count()),
            'completed_items' => $checklists->sum(fn ($c) => $c->items->where('is_completed', true)->count()),
            'progress_percentage' => $this->research_progress,
        ];
    }

    /**
     * The attributes that should be mutated to dates.
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
            'birthday' => 'datetime',
            'deathday' => 'datetime',
            'burial_day' => 'datetime',
            'chan' => 'datetime',
            'pedigree' => PedigreeType::class,
        ];
    }
}
