<?php

namespace App\Providers;

use App\contracts\TweetRepositoryInterface;
use App\contracts\UserRepositoryInterface;
use App\Repositories\TweetRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TweetRepositoryInterface::class, TweetRepository::class);
    }
}
