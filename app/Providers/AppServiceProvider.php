<?php

namespace App\Providers;

use App\Domain\FeedService;
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
        $this->app->extend(FeedService::class, function (FeedService $feedService) {
            $feedService->setConfig(config('feeds', []));

            return $feedService;
        });
    }
}
