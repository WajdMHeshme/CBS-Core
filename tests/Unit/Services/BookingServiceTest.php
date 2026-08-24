<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use App\Models\Car;
use App\Models\Booking;
use App\Models\BookingPlan;
use App\Services\BookingService;
use Database\Seeders\BookingPlanSeeder;
use Database\Seeders\RoleSeeder;

test('creates a booking successfully', function () {
    $this->seed(RoleSeeder::class);
    $this->seed(BookingPlanSeeder::class);

    $user = User::factory()->create();

    $car = Car::factory()->create([
        'price_per_day' => 100,
    ]);

    $plan = BookingPlan::where('name', 'Basic')->first();

    $booking = app(BookingService::class)->create([
        'car_id' => $car->id,
        'booking_plan_id' => $plan->id,
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-05',
    ], $user->id);

    // 6. Assertions
    expect($booking)->toBeInstanceOf(Booking::class);

    expect($booking->user_id)->toBe($user->id);

    expect($booking->car_id)->toBe($car->id);

    expect($booking->booking_plan_id)->toBe($plan->id);

    expect($booking->status)->toBe('pending');

    expect((float) $booking->final_price)->toBe(400.0);
});
