<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name'      => 'Admin User',
            'email'     => 'admin@example.com',
            'password'  => Hash::make('password123'),
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        // Employee
        $employee = User::create([
            'name'      => 'Test Employee',
            'email'     => 'employee@test.com',
            'password'  => Hash::make('12345678'),
            'is_active' => true,
        ]);
        $employee->assignRole('employee');
        // Lessor
        $lessor = User::create([
            'name'      => 'Test Lessor',
            'email'     => 'lessor@test.com',
            'password'  => Hash::make('12345678'),
            'is_active' => true, 
        ]);
        $lessor->assignRole('lessor');

        // Customers
        User::factory(10)->create()->each(function ($user) {
            $user->assignRole('customer');
        });
    }
}
