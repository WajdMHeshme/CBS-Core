<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'employee_id' => null, // رح نعبّيه بالSeeder
            'car_id' => Car::inRandomOrder()->value('id'),

            'scheduled_at' => Carbon::now()
                ->addDays($this->faker->numberBetween(1, 20))
                ->setTime($this->faker->numberBetween(9, 18), 0),

            'status' => $this->faker->randomElement([
                'pending',
                'approved',
                'rescheduled',
                'canceled',
                'completed',
            ]),
        ];
    }
}
