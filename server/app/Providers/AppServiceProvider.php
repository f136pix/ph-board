<?php

namespace App\Providers;

use App\Http\Service\AuthService;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        {
            $this->app->singleton(AuthService::class, function ($app) {
                return new AuthService();
            });
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();

        \Gate::define('view',function(User $user, $model) {
            return $user->hasAccess("view_{$model}") || $user->hasAccess("edit_{$model}");
        });

        \Gate::define('edit',fn(User $user, $model) => $user->hasAccess("edit_{$model}"));
    }
}
