<?php
// app/Http/Controllers/LocationController.php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Item;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function searchNearby(Request $request)
    {
        $validator = validator($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid coordinates'], 400);
        }

        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius ?? 10; // default 10km

        $locations = Location::withinRadius($latitude, $longitude, $radius)
            ->with(['items' => function($query) {
                $query->active()->with(['category', 'user']);
            }])
            ->get();

        $items = collect();
        foreach ($locations as $location) {
            foreach ($location->items as $item) {
                $item->distance = $location->distance;
                $items->push($item);
            }
        }

        $items = $items->sortBy('distance')->values();

        return response()->json([
            'items' => $items->map(function($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'status' => $item->status,
                    'status_label' => $item->status_label,
                    'category' => $item->category->name,
                    'location' => [
                        'address' => $item->location->address,
                        'city' => $item->location->city,
                        'latitude' => $item->location->latitude,
                        'longitude' => $item->location->longitude,
                    ],
                    'distance' => round($item->distance, 2),
                    'event_date' => $item->event_date_formatted,
                    'first_photo' => $item->first_photo,
                    'url' => route('items.show', $item->id),
                ];
            })
        ]);
    }

    public function getCities(Request $request)
    {
        $search = $request->get('q', '');
        
        $cities = Location::select('city')
            ->when($search, function($query) use ($search) {
                $query->where('city', 'like', "%{$search}%");
            })
            ->groupBy('city')
            ->orderBy('city')
            ->limit(20)
            ->pluck('city');

        return response()->json($cities);
    }

    public function getProvinces(Request $request)
    {
        $search = $request->get('q', '');
        
        $provinces = Location::select('province')
            ->whereNotNull('province')
            ->when($search, function($query) use ($search) {
                $query->where('province', 'like', "%{$search}%");
            })
            ->groupBy('province')
            ->orderBy('province')
            ->limit(20)
            ->pluck('province');

        return response()->json($provinces);
    }

    public function getLocationsByCity($city)
    {
        $locations = Location::byCity($city)
            ->with(['items' => function($query) {
                $query->active()->latest();
            }])
            ->get();

        return response()->json($locations);
    }

    public function reverseGeocode(Request $request)
    {
        $validator = validator($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid coordinates'], 400);
        }

        // TODO: Implement reverse geocoding using external API
        // For now, return mock data
        return response()->json([
            'address' => 'Alamat tidak diketahui',
            'city' => 'Kota tidak diketahui',
            'province' => 'Provinsi tidak diketahui',
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);
    }

    public function geocode(Request $request)
    {
        $validator = validator($request->all(), [
            'address' => 'required|string|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Address is required'], 400);
        }

        // TODO: Implement geocoding using external API
        // For now, return mock data
        return response()->json([
            'results' => [
                [
                    'address' => $request->address,
                    'latitude' => -6.2088,
                    'longitude' => 106.8456,
                    'city' => 'Jakarta',
                    'province' => 'DKI Jakarta',
                ]
            ]
        ]);
    }

    public function mapData(Request $request)
    {
        $query = Item::with(['location', 'category'])
            ->active();

        // Filter berdasarkan status
        if ($request->status && in_array($request->status, ['lost', 'found'])) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan kategori
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter berdasarkan area jika ada bounds
        if ($request->north && $request->south && $request->east && $request->west) {
            $query->whereHas('location', function($q) use ($request) {
                $q->whereBetween('latitude', [$request->south, $request->north])
                  ->whereBetween('longitude', [$request->west, $request->east]);
            });
        }

        $items = $query->limit(500)->get(); // Limit untuk performa

        return response()->json([
            'markers' => $items->map(function($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'status' => $item->status,
                    'category' => $item->category->name,
                    'latitude' => $item->location->latitude,
                    'longitude' => $item->location->longitude,
                    'address' => $item->location->address,
                    'city' => $item->location->city,
                    'event_date' => $item->event_date_formatted,
                    'first_photo' => $item->first_photo,
                    'url' => route('items.show', $item->id),
                ];
            })
        ]);
    }
}