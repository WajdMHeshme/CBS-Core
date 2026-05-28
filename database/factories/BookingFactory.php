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
        $startDate = Carbon::now()
            ->addDays($this->faker->numberBetween(1, 20));

        $endDate = (clone $startDate)
            ->addDays($this->faker->numberBetween(1, 10));

        return [
            'user_id' => User::inRandomOrder()->value('id'),

            'employee_id' => null,

            'car_id' => Car::inRandomOrder()->value('id'),

            'start_date' => $startDate->toDateString(),

            'end_date' => $endDate->toDateString(),

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
