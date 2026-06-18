<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Profile;
use App\Models\ProfileReport;
use App\Models\User;
use App\Observers\ProfileObserver;
use App\Policies\ProfilePolicy;
use App\Policies\ProfileReportPolicy;
use App\Policies\UserPolicy;
use App\Services\AuthService;
use App\Services\CityService;
use App\Services\MediaService;
use App\Services\ProfileService;
use App\View\Composers\LayoutComposer;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(MediaService::class);
        $this->app->singleton(CityService::class);
        $this->app->singleton(ProfileService::class, function ($app) {
            return new ProfileService($app->make(MediaService::class));
        });
        $this->app->singleton(AuthService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Profile::class, ProfilePolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(ProfileReport::class, ProfileReportPolicy::class);

        View::composer('layouts.app', LayoutComposer::class);

        Profile::observe(ProfileObserver::class);
    }
}
