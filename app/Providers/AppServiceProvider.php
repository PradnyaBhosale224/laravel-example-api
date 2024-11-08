<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Define gates
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('user', function (User $user) {
            return $user->role === 'user';
        });
    }
}
