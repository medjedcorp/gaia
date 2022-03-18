<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Train' => 'App\Policies\TrainPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isSystem', function ($user) {
            return $user->role == 'system';
        });
        Gate::define('isAdmin', function ($user) {
            return ($user->role == 'system' or $user->role == 'admin');
        });
        Gate::define('isUser', function ($user) {
            return ($user->role == 'system' or $user->role == 'admin' or $user->role == 'user');
        });
    }
}
