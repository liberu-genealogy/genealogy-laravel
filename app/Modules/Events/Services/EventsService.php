<?php

namespace App\Modules\Events\Services;

use App\Models\PersonEvent;
use App\Models\Person;
use App\Models\Place;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EventsService
{
    /**
     * Create a new event.
     */
    public function createEvent(array $data): PersonEvent
    {
        return PersonEvent::create([
            'person_id' => $data['person_id'],
            'title' => $data['type'],
            'date' => $data['date'] ?? null,
            'places_id' => $data['place_id'] ?? null,
            'description' => $data['description'] ?? null,
        ]);
    }

    /**
     * Update event information.
     */
    public function updateEvent(PersonEvent $event, array $data): PersonEvent
    {
        $event->update($data);
        return $event->fresh();
    }

    /**
     * Get events for a person.
     */
    public function getPersonEvents(Person $person): Collection
    {
        return PersonEvent::where('person_id', $person->id)
            ->with(['place'])
            ->orderBy('date')
            ->get();
    }

    /**
     * Get events by type.
     */
    public function getEventsByType(string $type): Collection
    {
        return PersonEvent::where('title', $type)
            ->with(['person', 'place'])
            ->orderBy('date')
            ->get();
    }

    /**
     * Get events in date range.
     */
    public function getEventsInDateRange(string $startDate, string $endDate): Collection
    {
        return PersonEvent::whereBetween('date', [$startDate, $endDate])
            ->with(['person', 'place'])
            ->orderBy('date')
            ->get();
    }

    /**
     * Search events.
     */
    public function searchEvents(string $query, int $limit = 50): Collection
    {
        return PersonEvent::where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orWhereHas('person', function ($q) use ($query) {
                $q->where('givn', 'LIKE', "%{$query}%")
                  ->orWhere('surn', 'LIKE', "%{$query}%");
            })
            ->with(['person', 'place'])
            ->limit($limit)
            ->get();
    }

    /**
     * Get event statistics.
     */
    public function getEventStatistics(): array
    {
        $total = PersonEvent::count();
        $byType = PersonEvent::selectRaw('title, COUNT(*) as count')
            ->groupBy('title')
            ->pluck('count', 'title')
            ->toArray();

        return [
            'total_events' => $total,
            'events_by_type' => $byType,
            'events_with_dates' => PersonEvent::whereNotNull('date')->count(),
            'events_with_places' => PersonEvent::whereNotNull('places_id')->count(),
            'recent_events' => $this->getRecentEvents(10),
        ];
    }

    /**
     * Get recent events.
     */
    protected function getRecentEvents(int $limit = 10): Collection
    {
        return PersonEvent::with(['person', 'place'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Validate event date.
     */
    public function validateEventDate(string $date): bool
    {
        return strtotime($date) !== false;
    }

    /**
     * Format event date for display.
     */
    public function formatEventDate(?string $date): string
    {
        if (!$date) {
            return 'Unknown';
        }

        $timestamp = strtotime($date);
        if (!$timestamp) {
            return $date; // Return original if can't parse
        }

        return date('F j, Y', $timestamp);
    }

    /**
     * Get event types.
     */
    public function getEventTypes(): array
    {
        return config('genealogy.events.types', [
            'BIRT' => 'Birth',
            'DEAT' => 'Death',
            'MARR' => 'Marriage',
            'DIV' => 'Divorce',
            'BURI' => 'Burial',
            'BAPM' => 'Baptism',
            'CONF' => 'Confirmation',
            'GRAD' => 'Graduation',
            'OCCU' => 'Occupation',
            'RESI' => 'Residence',
            'EMIG' => 'Emigration',
            'IMMI' => 'Immigration',
        ]);
    }

    /**
     * Delete event.
     */
    public function deleteEvent(PersonEvent $event): bool
    {
        return $event->delete();
    }

    /**
     * Export event data.
     */
    public function exportEventData(PersonEvent $event): array
    {
        return [
            'id' => $event->id,
            'type' => $event->title,
            'type_name' => $this->getEventTypes()[$event->title] ?? $event->title,
            'date' => $event->date,
            'formatted_date' => $this->formatEventDate($event->date),
            'description' => $event->description,
            'person' => $event->person ? [
                'id' => $event->person->id,
                'name' => $event->person->fullname(),
            ] : null,
            'place' => $event->place ? [
                'id' => $event->place->id,
                'name' => $event->place->name,
            ] : null,
        ];
    }
}