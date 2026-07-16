<?php

namespace App\Livewire;

use App\Models\Family;
use App\Models\FamilyEvent;
use App\Models\Person;
use App\Models\PersonEvent;
use App\Services\HistoricalEventService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class TimelineComponent extends Component
{
    public ?int $personId = null;

    public ?int $familyId = null;

    public array $events = [];

    public ?array $selectedEvent = null;

    protected HistoricalEventService $historicalEventService;

    public function boot(HistoricalEventService $historicalEventService): void
    {
        $this->historicalEventService = $historicalEventService;
    }

    public function mount(?int $personId = null, ?int $familyId = null): void
    {
        $this->personId = $personId;
        $this->familyId = $familyId;
        $this->loadEvents();
    }

    /**
     * Load person events and historical events, merge them into $this->events
     * in the format expected by the front-end timeline library.
     */
    public function loadEvents($start = null, $end = null): void
    {
        // Keyed by item id so an event reached via more than one source dedups.
        $items = [];
        $dates = [];

        // Person mode (back-compat): a single person's own events.
        if ($this->personId) {
            $person = Person::with('events.place')->find($this->personId);

            if ($person) {
                foreach ($person->events as $e) {
                    if (! $e->date) {
                        continue;
                    }
                    $items['p_'.$e->id] = $this->personEventItem($e);
                    $dates[] = (string) $e->date;
                }
            }
        }

        // Family mode: every member's person-events + the family's own events.
        if ($this->familyId) {
            $family = Family::find($this->familyId);

            if ($family) {
                $memberIds = collect([$family->husband_id, $family->wife_id])
                    ->merge(Person::where('child_in_family_id', $family->id)->pluck('id'))
                    ->filter()
                    ->unique();

                foreach (Person::with('events.place')->whereIn('id', $memberIds)->get() as $member) {
                    foreach ($member->events as $e) {
                        if (! $e->date) {
                            continue;
                        }
                        $items['p_'.$e->id] = $this->personEventItem($e);
                        $dates[] = (string) $e->date;
                    }
                }

                foreach (FamilyEvent::with('place')->where('family_id', $family->id)->get() as $fe) {
                    if (! $fe->date) {
                        continue;
                    }
                    $items['f_'.$fe->id] = [
                        'id' => 'f_'.$fe->id,
                        'title' => $fe->title,
                        'content' => $fe->title,
                        'start' => (string) $fe->date,
                        'group' => 'family',
                        'type' => 'family',
                        'place' => $fe->place?->name ?? null,
                        'description' => $fe->description ?? null,
                    ];
                    $dates[] = (string) $fe->date;
                }
            }
        }

        // Determine period to query historical events
        if (! $start || ! $end) {
            if ($this->familyId && $dates) {
                // Span the family's collected events.
                $start = min($dates);
                $end = max($dates);
            } elseif (isset($person) && ($person->birthday || $person->deathday)) {
                $start = $person->birthday ?? now()->subYears(120)->toDateString();
                $end = $person->deathday ?? now()->toDateString();
            } else {
                $start = now()->subYears(100)->toDateString();
                $end = now()->toDateString();
            }
        }

        $historical = $this->historicalEventService->fetchForPeriod($start, $end);

        foreach ($historical as $h) {
            if (! $h->date) {
                continue;
            }

            $items['h_'.$h->id] = [
                'id' => 'h_'.$h->id,
                'title' => $h->title,
                'content' => $h->title,
                'start' => (string) $h->date,
                'group' => 'historical',
                'type' => 'historical',
                'place' => $h->place,
                'country' => $h->country,
                'description' => $h->description,
                'source_url' => $h->source_url,
            ];
        }

        $items = array_values($items);

        // Sort items by start date
        usort($items, fn (array $a, array $b) => strcmp($a['start'] ?? '', $b['start'] ?? ''));

        $this->events = $items;
    }

    /**
     * Map a PersonEvent to the timeline item shape shared by person and family modes.
     */
    private function personEventItem(PersonEvent $e): array
    {
        return [
            'id' => 'p_'.$e->id,
            'title' => $e->title,
            'content' => $e->title,
            'start' => (string) $e->date,
            'group' => 'family',
            'type' => 'family',
            'place' => $e->place?->name ?? null,
            'description' => $e->description ?? null,
        ];
    }

    /**
     * JS calls Livewire via Livewire.dispatch('timelineItemClicked', { id: '...' })
     */
    #[On('timelineItemClicked')]
    public function openEventFromJs(string $id): void
    {
        $item = collect($this->events)->firstWhere('id', $id);
        if ($item) {
            $this->selectedEvent = $item;
        }
    }

    public function closeModal(): void
    {
        $this->selectedEvent = null;
    }

    public function render(): Factory|View
    {
        return view('livewire.timeline-component', [
            'events' => $this->events,
            'selectedEvent' => $this->selectedEvent,
            'mode' => $this->familyId ? 'family' : 'person',
        ]);
    }
}
