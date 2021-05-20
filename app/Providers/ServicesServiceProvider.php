<?php

namespace App\Providers;

use App\Services\Applications\TransferService;
use App\Services\Applications\UserService;
use App\Services\Contracts\TransferServiceContract;
use App\Services\Contracts\UserServiceContract;
use Illuminate\Support\ServiceProvider;

class ServicesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserServiceContract::class, UserService::class);

        $this->app->bind(TransferServiceContract::class,TransferService::class);
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
