<?php

namespace App\Providers;

use App\Services\MultiChannelMessageService;
use App\Services\PostmarkEmailService;
use App\Services\WhatsappMessageService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('message.drivers', function () {
            return [
                'email' => new PostmarkEmailService(
                    config('services.message.postmark.token'),
                    config('services.message.postmark.from')
                ),
                'whatsapp' => new WhatsappMessageService(
                    config('services.message.whatsapp_token') ?? ""
                ),
            ];
        });

        $this->app->singleton(MultiChannelMessageService::class, function ($app) {
            return new MultiChannelMessageService($app->make('message.drivers'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
