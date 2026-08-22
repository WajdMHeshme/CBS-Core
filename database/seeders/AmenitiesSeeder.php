<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            'Automatic Transmission',
            'Manual Transmission',
            'Air Conditioning',
            'GPS Navigation',
            'Bluetooth',
            'Cruise Control',
            'Parking Sensors',
            'Rear Camera',
            '4x4',
            'Diesel',
            'Hybrid',
            'Electric',                
            'Sunroof',
        ];

        foreach ($features as $name) {
            Amenity::firstOrCreate([
                'name' => $name,
            ]);
        }
    }
}
