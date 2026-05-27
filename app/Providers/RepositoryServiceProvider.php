<?php

namespace App\Providers;

use App\Repositories\Eloquent\CarRepository;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\CarRepositoryInterface;
use App\Repositories\Contracts\CommissionRepositoryInterface;
use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\ProfileRepositoryInterface;
use App\Repositories\Eloquent\BookingRepository;
use App\Repositories\Eloquent\CommissionRepository;
use App\Repositories\Eloquent\ProfileRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            ProfileRepositoryInterface::class,
            ProfileRepository::class
        );
        $this->app->bind(
            BookingRepositoryInterface::class,
            BookingRepository::class
        );

        $this->app->bind(
            CarRepositoryInterface::class,
            CarRepository::class
        );

        $this->app->bind(
            CommissionRepositoryInterface::class,
            CommissionRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
