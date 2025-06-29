<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\QrCodeRepositoryInterface;
use App\Repositories\Contracts\ShortUrlRepositoryInterface;
use App\Repositories\QrCodeRepository;
use App\Repositories\ShortUrlRepository;
use App\Services\Contracts\QrCodeServiceInterface;
use App\Services\Contracts\ShortUrlServiceInterface;
use App\Services\QrCodeService;
use App\Services\ShortUrlService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repository bindings
        $this->app->bind(ShortUrlRepositoryInterface::class, ShortUrlRepository::class);
        $this->app->bind(QrCodeRepositoryInterface::class, QrCodeRepository::class);

        // Service bindings
        $this->app->bind(ShortUrlServiceInterface::class, ShortUrlService::class);
        $this->app->bind(QrCodeServiceInterface::class, QrCodeService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
