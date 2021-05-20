<?php

namespace App\Providers;

use App\Repositories\Contracts\TransferRepositoryContract;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Repositories\Eloquent\TransferRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);

        $this->app->bind(TransferRepositoryContract::class,TransferRepository::class);
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
