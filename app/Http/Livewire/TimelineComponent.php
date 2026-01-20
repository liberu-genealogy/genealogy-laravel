<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Person;
use App\Services\HistoricalEventService;

class TimelineComponent extends Component
{
    public ?int $personId = null;
    public array $events = [];
    public ?array $selectedEvent = null;

    protected $listeners = [
        'timelineItemClicked' => 'openEventFromJs',
    ];

    protected HistoricalEventService $historicalEventService;

    public function boot(HistoricalEventService $historicalEventService)
    {
        $this->historicalEventService = $historicalEventService;
    }

    public function mount(?int $personId = null)
    {
        $this->personId = $personId;
        $this->loadEvents();
    }

    /**
     * Load person events and historical events, merge them into $this->events
     * in the format expected by the front-end timeline library.
     */
    public function loadEvents($start = null, $end = null)
    {
        $items = [];

        if ($this->personId) {
            $person = Person::with('events.place')->find($this->personId);

            if ($person) {
                foreach ($person->events as $e) {
                    // Ensure a parsable date exists
                    if (!$e->date) {
                        continue;
                    }

                    $items[] = [
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
            }
        }

        // Determine period to query historical events
        if (!$start || !$end) {
            if (isset($person) && ($person->birthday || $person->deathday)) {
                $start = $person->birthday ?? ($person->deathday ? now()->subYears(120)->toDateString() : now()->subYears(120)->toDateString());
                $end = $person->deathday ?? now()->toDateString();
            } else {
                $start = now()->subYears(100)->toDateString();
                $end = now()->toDateString();
            }
        }

        $historical = $this->historicalEventService->fetchForPeriod($start, $end);

        foreach ($historical as $h) {
            if (!$h->date) {
                continue;
            }

            $items[] = [
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

        // Sort items by start date
        usort($items, function ($a, $b) {
            return strcmp($a['start'] ?? '', $b['start'] ?? '');
        });

        $this->events = $items;
    }

    /**
     * JS calls Livewire via window.Livewire.emit('timelineItemClicked', id)
     */
    public function openEventFromJs($id)
    {
        $item = collect($this->events)->firstWhere('id', $id);
        if ($item) {
            $this->selectedEvent = $item;
        }
    }

    public function closeModal()
    {
        $this->selectedEvent = null;
    }

    public function render()
    {
        return view('livewire.timeline-component', [
            'events' => $this->events,
            'selectedEvent' => $this->selectedEvent,
        ]);
    }
}
