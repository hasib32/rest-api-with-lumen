<?php

namespace App\Providers;

use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
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
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->input('api_token')) {
                return User::where('api_token', $request->input('api_token'))->first();
            }
        });

        Passport::tokensCan([
            'admin' => 'Admin user scope',
            'basic' => 'Basic user scope',
            'users' => 'Users scope',
            'users:list' => 'Users scope',
            'users:read' => 'Users scope for reading records',
            'users:write' => 'Users scope for writing records',
            'users:create' => 'Users scope for creating records',
            'users:delete' => 'Users scope for deleting records',
        ]);

        //Register all policies here
        Gate::policy(User::class, UserPolicy::class);
    }
}
