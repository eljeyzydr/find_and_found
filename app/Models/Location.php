<?php
// app/Models/Location.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'latitude',
        'longitude',
        'city',
        'province',
        'postal_code',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    // Scopes
    public function scopeByCity($query, $city)
    {
        return $query->where('city', 'like', "%{$city}%");
    }

    public function scopeByProvince($query, $province)
    {
        return $query->where('province', 'like', "%{$province}%");
    }

    // Helper Methods
    public function getFullAddressAttribute()
    {
        $address = $this->address;
        if ($this->city) {
            $address .= ', ' . $this->city;
        }
        if ($this->province) {
            $address .= ', ' . $this->province;
        }
        return $address;
    }

    public function getCoordinatesAttribute()
    {
        return [
            'lat' => $this->latitude,
            'lng' => $this->longitude
        ];
    }

    // Calculate distance to another location in kilometers
    public function distanceTo($latitude, $longitude)
    {
        $earthRadius = 6371; // Earth radius in kilometers

        $deltaLatitude = deg2rad($latitude - $this->latitude);
        $deltaLongitude = deg2rad($longitude - $this->longitude);

        $a = sin($deltaLatitude / 2) * sin($deltaLatitude / 2) +
             cos(deg2rad((float)$this->latitude)) * cos(deg2rad((float)$latitude)) *
             sin($deltaLongitude / 2) * sin($deltaLongitude / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return round($distance, 2);
    }

    // Scope untuk mencari lokasi dalam radius tertentu
    public function scopeWithinRadius($query, $latitude, $longitude, $radius = 10)
    {
        return $query->selectRaw("
                *,
                (6371 * acos(cos(radians(?)) 
                * cos(radians(latitude)) 
                * cos(radians(longitude) - radians(?)) 
                + sin(radians(?)) 
                * sin(radians(latitude)))) AS distance
            ", [$latitude, $longitude, $latitude])
            ->havingRaw('distance <= ?', [$radius])
            ->orderBy('distance');
    }
}