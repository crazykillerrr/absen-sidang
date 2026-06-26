<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\RuangSidangRepositoryInterface::class,
            \App\Repositories\Eloquent\RuangSidangRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\PerkaraRepositoryInterface::class,
            \App\Repositories\Eloquent\PerkaraRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\JadwalSidangRepositoryInterface::class,
            \App\Repositories\Eloquent\JadwalSidangRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\PihakSidangRepositoryInterface::class,
            \App\Repositories\Eloquent\PihakSidangRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\KehadiranRepositoryInterface::class,
            \App\Repositories\Eloquent\KehadiranRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\QrCodeRepositoryInterface::class,
            \App\Repositories\Eloquent\QrCodeRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\NotifikasiRepositoryInterface::class,
            \App\Repositories\Eloquent\NotifikasiRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
