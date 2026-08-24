<?php

use App\Models\Booking;
use App\Models\BookingPlan;
use App\Models\Car;
use App\Models\User;
use Database\Seeders\BookingPlanSeeder;
use Database\Seeders\RoleSeeder;

it('creates a booking successfully', function () {
    $this->seed(RoleSeeder::class);
    $this->seed(BookingPlanSeeder::class);

    $user = User::factory()->create([
        'is_active' => true,
    ]);
    $car = Car::factory()->create([
        'price_per_day' => 100,
    ]);

    $plan = BookingPlan::where('name', 'Basic')->first();

    $response = $this
        ->actingAs($user, 'sanctum')
        ->postJson('/api/v1/bookings', [
            'car_id' => $car->id,
            'booking_plan_id' => $plan->id,
            'start_date' => '2026-09-01',
            'end_date' => '2026-09-05',
        ]);

    $response->assertStatus(201);

    $response->assertJson([
        'message' => __('messages.booking.created'),
    ]);

    $response->assertJsonStructure([
        'message',
        'data',
    ]);

    expect($response->json('data'))->not->toBeNull();

    $this->assertDatabaseHas('bookings', [
        'user_id' => $user->id,
        'car_id' => $car->id,
        'booking_plan_id' => $plan->id,
        'status' => 'pending',
    ]);
});


// user active check


it('rejects booking for a disabled user', function () {
    $this->seed(RoleSeeder::class);
    $this->seed(BookingPlanSeeder::class);

    $user = User::factory()->create([
        'is_active' => false,
    ]);

    $car = Car::factory()->create(['price_per_day' => 100]);
    $plan = BookingPlan::where('name', 'Basic')->first();

    $response = $this
        ->actingAs($user, 'sanctum')
        ->postJson('/api/v1/bookings', [
            'car_id' => $car->id,
            'booking_plan_id' => $plan->id,
            'start_date' => '2026-09-01',
            'end_date' => '2026-09-05',
        ]);

    $response->assertStatus(403);

    $this->assertDatabaseMissing('bookings', [
        'user_id' => $user->id,
        'car_id' => $car->id,
    ]);
});



// unauthenticated user check



it('rejects booking creation for guests', function () {
    $this->seed(RoleSeeder::class);
    $this->seed(BookingPlanSeeder::class);

    $carOwner = User::factory()->create();

    $car = Car::factory()->create([
        'user_id' => $carOwner->id,
        'price_per_day' => 100,
    ]);

    $plan = BookingPlan::where('name', 'Basic')->first();

    $response = $this->postJson('/api/v1/bookings', [
        'car_id' => $car->id,
        'booking_plan_id' => $plan->id,
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-05',
    ]);

    $response->assertStatus(401);
});


//check unvalid data

it('rejects booking creation for unvalid data' , function(){
    $this->seed(RoleSeeder::class);
    $this->seed(BookingPlanSeeder::class);

    $user = User::factory()->create([
        'is_active' => true,
    ]);

    $car = Car::factory()->create(['price_per_day' => 100]);
    $plan = BookingPlan::where('name', 'Basic')->first();

    $response = $this
        ->actingAs($user, 'sanctum')
        ->postJson('/api/v1/bookings', [
            'car_id' => $car->id,
            'booking_plan_id' => $plan->id,
            'start_date' => '2026-09-05',
            'end_date' => '2026-09-01',
        ]);

    $response->assertStatus(422);

    $response->assertJsonValidationErrors(['end_date']);
});
