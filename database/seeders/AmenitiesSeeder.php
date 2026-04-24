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
            'Automatic Transmission',   // قير أوتوماتيك
            'Manual Transmission',      // قير عادي
            'Air Conditioning',         // تكييف
            'GPS Navigation',           // نظام ملاحة
            'Bluetooth',                // بلوتوث
            'Cruise Control',          // مثبت سرعة
            'Parking Sensors',         // حساسات ركن
            'Rear Camera',             // كاميرا خلفية
            '4x4',                     // دفع رباعي
            'Diesel',                  // ديزل
            'Hybrid',                 // هايبرد
            'Electric',                // كهربائي   
            'Sunroof',
        ];

        foreach ($features as $name) {
            Amenity::firstOrCreate([
                'name' => $name,
            ]);
        }
    }
}
