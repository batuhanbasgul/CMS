<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Is the user master gate.
         */
        Gate::define('master', function ($user) {
            if ($user->user_role == 'master') {
                return true;
            } else {
                return false;
            }
        });

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Is the user admin gate.
         */
        Gate::define('admin', function ($user) {
            if ($user->user_role == 'admin') {
                return true;
            } else {
                return false;
            }
        });

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Is the user's authorization is equol or higher than target user's authorization.
         */
        Gate::define('crud-show', function ($user, $target_user) {
            if ($user->user_role == 'master') {
                return true;
            } else if ($user->user_role == 'admin') {
                if ($target_user->user_role == 'master') {
                    return false;
                } else {
                    return true;
                }
            }
        });
    }
}
