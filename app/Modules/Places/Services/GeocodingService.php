<?php

namespace App\Modules\Places\Services;

use App\Models\Place;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GeocodingService
{
    /**
     * Geocode a place name to coordinates.
     */
    public function geocodePlace(string $placeName): ?array
    {
        $cacheKey = 'geocode_' . md5($placeName);
        
        return Cache::remember($cacheKey, 86400, function () use ($placeName) {
            return $this->performGeocoding($placeName);
        });
    }

    /**
     * Reverse geocode coordinates to place information.
     */
    public function reverseGeocode(float $latitude, float $longitude): ?array
    {
        $cacheKey = 'reverse_geocode_' . md5("{$latitude},{$longitude}");
        
        return Cache::remember($cacheKey, 86400, function () use ($latitude, $longitude) {
            return $this->performReverseGeocoding($latitude, $longitude);
        });
    }

    /**
     * Update place coordinates using geocoding.
     */
    public function updatePlaceCoordinates(Place $place): bool
    {
        if ($place->latitude && $place->longitude) {
            return true; // Already has coordinates
        }

        $placeName = $this->buildPlaceNameForGeocoding($place);
        $coordinates = $this->geocodePlace($placeName);

        if ($coordinates) {
            $place->update([
                'latitude' => $coordinates['latitude'],
                'longitude' => $coordinates['longitude'],
            ]);
            return true;
        }

        return false;
    }

    /**
     * Batch geocode multiple places.
     */
    public function batchGeocodePlaces(array $places): array
    {
        $results = [];
        
        foreach ($places as $place) {
            $results[$place->id] = $this->updatePlaceCoordinates($place);

            // Add delay to respect API rate limits
            usleep(100000); // 0.1 second delay
        }
        
        return $results;
    }

    /**
     * Perform actual geocoding using external service.
     */
    protected function performGeocoding(string $placeName): ?array
    {
        try {
            // Using OpenStreetMap Nominatim as example (free service)
            $response = Http::get('https://nominatim.openstreetmap.org/search', [
                'q' => $placeName,
                'format' => 'json',
                'limit' => 1,
                'addressdetails' => 1,
            ]);

            if ($response->successful() && !empty($response->json())) {
                $data = $response->json()[0];
                
                return [
                    'latitude' => (float) $data['lat'],
                    'longitude' => (float) $data['lon'],
                    'display_name' => $data['display_name'],
                    'address' => $data['address'] ?? [],
                ];
            }
        } catch (\Exception $e) {
            \Log::warning('Geocoding failed for place: ' . $placeName, [
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }

    /**
     * Perform reverse geocoding.
     */
    protected function performReverseGeocoding(float $latitude, float $longitude): ?array
    {
        try {
            $response = Http::get('https://nominatim.openstreetmap.org/reverse', [
                'lat' => $latitude,
                'lon' => $longitude,
                'format' => 'json',
                'addressdetails' => 1,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'display_name' => $data['display_name'],
                    'address' => $data['address'] ?? [],
                    'place_type' => $data['type'] ?? null,
                ];
            }
        } catch (\Exception $e) {
            \Log::warning('Reverse geocoding failed for coordinates: ' . $latitude . ',' . $longitude, [
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }

    /**
     * Build place name string for geocoding.
     */
    protected function buildPlaceNameForGeocoding(Place $place): string
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
     * Validate coordinates.
     */
    public function validateCoordinates(float $latitude, float $longitude): bool
    {
        return $latitude >= -90 && $latitude <= 90 && 
               $longitude >= -180 && $longitude <= 180;
    }

    /**
     * Calculate distance between two places.
     */
    public function calculateDistance(Place $place1, Place $place2): ?float
    {
        if (!$place1->latitude || !$place1->longitude || 
            !$place2->latitude || !$place2->longitude) {
            return null;
        }

        return $this->haversineDistance(
            $place1->latitude, $place1->longitude,
            $place2->latitude, $place2->longitude
        );
    }

    /**
     * Calculate distance using Haversine formula.
     */
    protected function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}