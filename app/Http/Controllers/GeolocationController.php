<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Geolocation;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GeolocationController extends Controller
{
    private $geolocationApiKey;

    public function __construct()
    {
        $this->geolocationApiKey = env('GEOLOCATION_API_KEY');
    }

    /**
     * Get coordinates (latitude and longitude) for a given address.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCoordinates(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
        ]);

        $address = $request->input('address');
        $coordinates = $this->fetchCoordinates($address);

        if ($coordinates) {
            // Save to the database
            Geolocation::create([
                'user_id' => Auth::id(),
                'latitude' => $coordinates['lat'],
                'longitude' => $coordinates['lng'],
                'address' => $address,
                'requested_at' => now(),
            ]);

            return response()->json(['coordinates' => $coordinates], 200);
        } else {
            return response()->json(['message' => 'Unable to fetch coordinates'], 500);
        }
    }

    /**
     * Fetch coordinates (latitude and longitude) from Google Maps Geocoding API.
     *
     * @param  string  $address
     * @return array|null
     */
    private function fetchCoordinates($address)
    {
        $cacheKey = 'geolocation_' . md5($address);
        $cachedCoordinates = Cache::get($cacheKey);

        if ($cachedCoordinates) {
            return $cachedCoordinates;
        }

        $client = new Client();
        $url = 'https://maps.googleapis.com/maps/api/geocode/json';

        try {
            $response = $client->get($url, [
                'query' => [
                    'address' => $address,
                    'key' => $this->geolocationApiKey,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if ($data['status'] == 'OK') {
                $location = $data['results'][0]['geometry']['location'];
                $coordinates = ['lat' => $location['lat'], 'lng' => $location['lng']];
                Cache::put($cacheKey, $coordinates, 3600); // Cache for 1 hour
                return $coordinates;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            // Handle API request errors
            Log::error('Error fetching coordinates: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Reverse geocode (get address) for given latitude and longitude coordinates.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reverseGeocode(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $address = $this->fetchAddress($latitude, $longitude);

        if ($address) {
            // Save to the database
            Geolocation::create([
                'user_id' => Auth::id(),
                'latitude' => $latitude,
                'longitude' => $longitude,
                'address' => $address,
                'requested_at' => now(),
            ]);

            return response()->json(['address' => $address], 200);
        } else {
            return response()->json(['message' => 'Unable to fetch address'], 500);
        }
    }

    /**
     * Fetch address from Google Maps Geocoding API for given latitude and longitude coordinates.
     *
     * @param  float  $latitude
     * @param  float  $longitude
     * @return string|null
     */
    private function fetchAddress($latitude, $longitude)
    {
        $cacheKey = 'reverse_geolocation_' . md5("$latitude$longitude");
        $cachedAddress = Cache::get($cacheKey);

        if ($cachedAddress) {
            return $cachedAddress;
        }

        $client = new Client();
        $url = 'https://maps.googleapis.com/maps/api/geocode/json';

        try {
            $response = $client->get($url, [
                'query' => [
                    'latlng' => $latitude . ',' . $longitude,
                    'key' => $this->geolocationApiKey,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if ($data['status'] == 'OK') {
                $address = $data['results'][0]['formatted_address'];
                Cache::put($cacheKey, $address, 3600); // Cache for 1 hour
                return $address;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            // Handle API request errors
            Log::error('Error fetching address: ' . $e->getMessage());
            return null;
        }
    }
}
