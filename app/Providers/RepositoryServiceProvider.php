<?php

namespace App\Providers;

use App\Repositories\BaseRepository;
use App\Repositories\CardRepository;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use App\Repositories\Interfaces\CardRepositoryInterface;
use App\Services\CardService;
use App\Services\Interfaces\CardServiceInterface;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            CardServiceInterface::class,
            CardService::class
        );
        $this->app->singleton(
            BaseRepositoryInterface::class,
            BaseRepository::class
        );
        $this->app->singleton(
            CardRepositoryInterface::class,
            CardRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
