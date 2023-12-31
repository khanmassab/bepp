<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
        $this->registerPolicies();
        // Passport::routes();
        Passport::tokensExpireIn(now()->addWeek(1));
        Passport::refreshTokensExpireIn(now()->addWeek(1));
        Passport::personalAccessTokensExpireIn(now()->addWeek(1));
    }
}
