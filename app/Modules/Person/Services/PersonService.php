<?php

namespace App\Modules\Person\Services;

use App\Models\Person;
use App\Models\PersonEvent;
use App\Models\PersonName;
use App\Models\PersonAlia;
use App\Models\Place;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PersonService
{
    /**
     * Create a new person.
     */
    public function createPerson(array $data): Person
    {
        $person = Person::create([
            'givn' => $data['given_name'],
            'surn' => $data['surname'],
            'sex' => $data['sex'] ?? 'U',
            'description' => $data['description'] ?? '',
            'birthday' => $data['birth_date'] ?? null,
            'deathday' => $data['death_date'] ?? null,
        ]);

        // Add birth event if birth date provided
        if (!empty($data['birth_date'])) {
            $this->addPersonEvent($person, 'BIRT', $data['birth_date'], $data['birth_place'] ?? '');
        }

        // Add death event if death date provided
        if (!empty($data['death_date'])) {
            $this->addPersonEvent($person, 'DEAT', $data['death_date'], $data['death_place'] ?? '');
        }

        return $person;
    }

    /**
     * Update person information.
     */
    public function updatePerson(Person $person, array $data): Person
    {
        $person->update([
            'givn' => $data['given_name'] ?? $person->givn,
            'surn' => $data['surname'] ?? $person->surn,
            'sex' => $data['sex'] ?? $person->sex,
            'description' => $data['description'] ?? $person->description,
            'birthday' => $data['birth_date'] ?? $person->birthday,
            'deathday' => $data['death_date'] ?? $person->deathday,
        ]);

        return $person->fresh();
    }

    /**
     * Add an event to a person.
     */
    public function addPersonEvent(Person $person, string $type, string $date, string $place = '', string $description = ''): PersonEvent
    {
        return $person->addEvent($type, $date, $place, $description);
    }

    /**
     * Search for persons.
     */
    public function searchPersons(string $query, int $limit = 50): Collection
    {
        return Person::where('givn', 'LIKE', "%{$query}%")
            ->orWhere('surn', 'LIKE', "%{$query}%")
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->get();
    }

    /**
     * Get persons with pagination.
     */
    public function getPersonsPaginated(int $perPage = 20): LengthAwarePaginator
    {
        return Person::with(['events', 'childInFamily'])
            ->orderBy('surn')
            ->orderBy('givn')
            ->paginate($perPage);
    }

    /**
     * Get living persons.
     */
    public function getLivingPersons(): Collection
    {
        return Person::whereNull('deathday')
            ->orWhere('deathday', '>', now()->subYears(config('genealogy.privacy.living_years_threshold', 100)))
            ->get();
    }

    /**
     * Get deceased persons.
     */
    public function getDeceasedPersons(): Collection
    {
        return Person::whereNotNull('deathday')
            ->where('deathday', '<=', now()->subYears(config('genealogy.privacy.living_years_threshold', 100)))
            ->get();
    }

    /**
     * Get person statistics.
     */
    public function getPersonStatistics(): array
    {
        $total = Person::count();
        $living = $this->getLivingPersons()->count();
        $deceased = $this->getDeceasedPersons()->count();
        $males = Person::where('sex', 'M')->count();
        $females = Person::where('sex', 'F')->count();
        $unknown = Person::where('sex', 'U')->orWhereNull('sex')->count();

        return [
            'total' => $total,
            'living' => $living,
            'deceased' => $deceased,
            'males' => $males,
            'females' => $females,
            'unknown_sex' => $unknown,
            'with_birth_date' => Person::whereNotNull('birthday')->count(),
            'with_death_date' => Person::whereNotNull('deathday')->count(),
        ];
    }

    /**
     * Get person's timeline events.
     */
    public function getPersonTimeline(Person $person): Collection
    {
        return $person->events()
            ->with('place')
            ->orderBy('date')
            ->get()
            ->map(function ($event) {
                return [
                    'type' => $event->title,
                    'date' => $event->date,
                    'place' => $event->place?->name ?? '',
                    'description' => $event->description,
                ];
            });
    }

    /**
     * Merge two person records.
     */
    public function mergePersons(Person $primaryPerson, Person $duplicatePerson): Person
    {
        // Transfer events from duplicate to primary
        PersonEvent::where('person_id', $duplicatePerson->id)
            ->update(['person_id' => $primaryPerson->id]);

        // Update family relationships
        \DB::table('families')
            ->where('husband_id', $duplicatePerson->id)
            ->update(['husband_id' => $primaryPerson->id]);

        \DB::table('families')
            ->where('wife_id', $duplicatePerson->id)
            ->update(['wife_id' => $primaryPerson->id]);

        Person::where('child_in_family_id', $duplicatePerson->child_in_family_id)
            ->where('id', '!=', $duplicatePerson->id)
            ->update(['child_in_family_id' => $primaryPerson->child_in_family_id]);

        // Merge additional data
        if (empty($primaryPerson->description) && !empty($duplicatePerson->description)) {
            $primaryPerson->description = $duplicatePerson->description;
        }

        if (empty($primaryPerson->birthday) && !empty($duplicatePerson->birthday)) {
            $primaryPerson->birthday = $duplicatePerson->birthday;
        }

        if (empty($primaryPerson->deathday) && !empty($duplicatePerson->deathday)) {
            $primaryPerson->deathday = $duplicatePerson->deathday;
        }

        $primaryPerson->save();

        // Delete the duplicate person
        $duplicatePerson->delete();

        return $primaryPerson;
    }

    /**
     * Export person data.
     */
    public function exportPersonData(Person $person): array
    {
        return [
            'basic_info' => [
                'id' => $person->id,
                'given_name' => $person->givn,
                'surname' => $person->surn,
                'full_name' => $person->fullname(),
                'sex' => $person->getSex(),
                'birth_date' => $person->birthday?->format('Y-m-d'),
                'death_date' => $person->deathday?->format('Y-m-d'),
                'description' => $person->description,
            ],
            'events' => $this->getPersonTimeline($person)->toArray(),
            'family_relationships' => [
                'parents' => $person->parents()?->map(fn($p) => [
                    'id' => $p->id,
                    'name' => $p->fullname(),
                    'relationship' => $p->sex === 'M' ? 'Father' : 'Mother',
                ])->toArray() ?? [],
                'spouses' => $person->familiesAsHusband->merge($person->familiesAsWife)
                    ->map(function ($family) use ($person) {
                        $spouse = $person->sex === 'M' ? $family->wife : $family->husband;
                        return $spouse ? [
                            'id' => $spouse->id,
                            'name' => $spouse->fullname(),
                            'relationship' => 'Spouse',
                        ] : null;
                    })
                    ->filter()
                    ->values()
                    ->toArray(),
                'children' => $person->children->map(fn($c) => [
                    'id' => $c->id,
                    'name' => $c->fullname(),
                    'relationship' => $c->sex === 'M' ? 'Son' : 'Daughter',
                ])->toArray(),
            ],
        ];
    }
}
