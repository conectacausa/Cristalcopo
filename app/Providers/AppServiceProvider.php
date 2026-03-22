<?php

namespace App\Providers;

use App\Models\Cargos\Cargo;
use App\Models\EmpresaFilial;
use App\Policies\CargoPolicy;
use App\Policies\EmpresaFilialPolicy;
use Illuminate\Support\Facades\Gate;
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
        Gate::policy(EmpresaFilial::class, EmpresaFilialPolicy::class);
        Gate::policy(Cargo::class, CargoPolicy::class);
    }
}
