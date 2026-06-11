<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\BookingPlan;

class BookingPlanSeeder extends Seeder
{
    public function run(): void
    {
        BookingPlan::create([
            'name' => 'Basic',
            'description' => 'Non-cancelable booking',
            'cancellation_allowed' => false,
            'cancellation_hours_before' => 0,
            'extra_percentage' => 0,
        ]);

        BookingPlan::create([
            'name' => 'Flexible',
            'description' => 'Cancelable up to 24 hours before pickup',
            'cancellation_allowed' => true,
            'cancellation_hours_before' => 24,
            'extra_percentage' => 10,
        ]);
    }
}
