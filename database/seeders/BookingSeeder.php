<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $employee = User::where('email', 'employee@test.com')->first();

        if (!$employee) {
            $this->command->error('Employee not found');
            return;
        }

        $cars = Car::all();

        if ($cars->isEmpty()) {
            $this->command->error('No cars found. Run CarSeeder first.');
            return;
        }

        Booking::factory()
            ->count(40)
            ->sequence(fn() => ['car_id' => $cars->random()->id])
            ->create(['employee_id' => $employee->id]);
    }
}
