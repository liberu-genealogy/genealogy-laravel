<?php

namespace App\Modules\Places\Services;

use App\Models\Place;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PlacesService
{
    /**
     * Create a new place.
     */
    public function createPlace(array $data): Place
    {
        return Place::create([
            'name' => $data['name'],
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'country' => $data['country'] ?? null,
            'state' => $data['state'] ?? null,
            'city' => $data['city'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
        ]);
    }

    /**
     * Update place information.
     */
    public function updatePlace(Place $place, array $data): Place
    {
        $place->update($data);
        return $place->fresh();
    }

    /**
     * Search for places.
     */
    public function searchPlaces(string $query, int $limit = 50): Collection
    {
        return Place::where('name', 'LIKE', "%{$query}%")
            ->orWhere('city', 'LIKE', "%{$query}%")
            ->orWhere('state', 'LIKE', "%{$query}%")
            ->orWhere('country', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->get();
    }

    /**
     * Get places with pagination.
     */
    public function getPlacesPaginated(int $perPage = 20): LengthAwarePaginator
    {
        return Place::orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get places by country.
     */
    public function getPlacesByCountry(string $country): Collection
    {
        return Place::where('country', $country)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get place hierarchy.
     */
    public function getPlaceHierarchy(Place $place): array
    {
        $hierarchy = [];
        
        if ($place->city) {
            $hierarchy[] = $place->city;
        }
        
        if ($place->state) {
            $hierarchy[] = $place->state;
        }
        
        if ($place->country) {
            $hierarchy[] = $place->country;
        }
        
        return $hierarchy;
    }

    /**
     * Format place name with hierarchy.
     */
    public function formatPlaceName(Place $place): string
    {
        $parts = array_filter([
            $place->name,
            $place->city,
            $place->state,
            $place->country,
        ]);
        
        return implode(', ', $parts);
    }

    /**
     * Get places within radius.
     */
    public function getPlacesWithinRadius(float $latitude, float $longitude, float $radiusKm): Collection
    {
        // Using Haversine formula for distance calculation
        return Place::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw('*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance', 
                [$latitude, $longitude, $latitude])
            ->having('distance', '<', $radiusKm)
            ->orderBy('distance')
            ->get();
    }

    /**
     * Get place statistics.
     */
    public function getPlaceStatistics(): array
    {
        $total = Place::count();
        $withCoordinates = Place::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->count();
        
        $countries = Place::whereNotNull('country')
            ->distinct('country')
            ->count('country');
            
        return [
            'total_places' => $total,
            'with_coordinates' => $withCoordinates,
            'without_coordinates' => $total - $withCoordinates,
            'countries' => $countries,
            'most_used_places' => $this->getMostUsedPlaces(10),
        ];
    }

    /**
     * Get most frequently used places.
     */
    protected function getMostUsedPlaces(int $limit = 10): Collection
    {
        // This would need to be implemented based on actual usage tracking
        // For now, return places ordered by name
        return Place::orderBy('name')
            ->limit($limit)
            ->get();
    }

    /**
     * Standardize place name format.
     */
    public function standardizePlaceName(string $placeName): string
    {
        // Basic standardization - can be enhanced
        $parts = array_map('trim', explode(',', $placeName));
        $parts = array_filter($parts);
        
        return implode(', ', $parts);
    }

    /**
     * Merge duplicate places.
     */
    public function mergePlaces(Place $primaryPlace, Place $duplicatePlace): Place
    {
        // Update references to use primary place
        // This would need to be implemented based on actual relationships
        
        // Merge coordinates if primary doesn't have them
        if (!$primaryPlace->latitude && $duplicatePlace->latitude) {
            $primaryPlace->latitude = $duplicatePlace->latitude;
            $primaryPlace->longitude = $duplicatePlace->longitude;
        }
        
        $primaryPlace->save();
        
        // Delete duplicate
        $duplicatePlace->delete();
        
        return $primaryPlace;
    }

    /**
     * Export place data.
     */
    public function exportPlaceData(Place $place): array
    {
        return [
            'id' => $place->id,
            'name' => $place->name,
            'formatted_name' => $this->formatPlaceName($place),
            'hierarchy' => $this->getPlaceHierarchy($place),
            'coordinates' => [
                'latitude' => $place->latitude,
                'longitude' => $place->longitude,
            ],
            'location_details' => [
                'city' => $place->city,
                'state' => $place->state,
                'country' => $place->country,
                'postal_code' => $place->postal_code,
            ],
        ];
    }
}