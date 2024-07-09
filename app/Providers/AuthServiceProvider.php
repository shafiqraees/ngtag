<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Route;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        /*Passport::tokensCan([
            'common-users' => 'Common Users',
            'corporate-users' => 'Corporate Users',
            'admin-users' => 'Admin users',
            // Add more scopes as needed
        ]);

        Passport::setDefaultScope([
            'users',
        ]);*/
        $this->registerPolicies();

        Passport::tokensExpireIn(now()->addMinutes(config('auth.token_expiration.corp_customer')));
        Passport::tokensExpireIn(now()->addMinutes(config('auth.token_expiration.admin')));
        Passport::refreshTokensExpireIn(now()->addMinutes(config('auth.token_expiration.corp_customer')));
        Passport::personalAccessTokensExpireIn(now()->addMinutes(config('auth.token_expiration.corp_customer')));
    }
}
