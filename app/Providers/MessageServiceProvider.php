<?php

namespace App\Providers;

use App\Contracts\MessageServiceInterface;
use App\Services\FailoverMessageService;
use App\Services\MockMessageService;
use App\Services\PostmarkEmailService;
use App\Services\TwilioMessageService;
use App\Services\WhatsappMessageService;
use Illuminate\Support\ServiceProvider;

class MessageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(MessageServiceInterface::class, function ($app) {
            $fallbacks = [];

            $primary = new PostmarkEmailService(
                config('services.message.postmark.token'),
                config('services.message.postmark.from')
            );

            $fallbacks[] = new WhatsappMessageService(config('services.message.whatsapp_token') ?? "");
            $fallbacks[] = new TwilioMessageService(config('services.message.api_key'));
            if (config('app.debug')) {
                $fallbacks[] = new MockMessageService();
            }

            return new FailoverMessageService(array_merge([$primary], $fallbacks));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
