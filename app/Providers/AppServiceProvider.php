<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\PlantRepository;
use App\Interfaces\IPlantRepository;
use App\Repositories\UserPlantRepository;
use App\Interfaces\IUserPlantRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IPlantRepository::class, PlantRepository::class);
        $this->app->bind(IUserPlantRepository::class, UserPlantRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
