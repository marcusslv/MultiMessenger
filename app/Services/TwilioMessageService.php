<?php

namespace App\Services;

use App\Contracts\MessageServiceInterface;
use Illuminate\Support\Facades\Log;

class TwilioMessageService implements MessageServiceInterface
{
    protected string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function send(string $to, string $message, array $options = []): bool
    {
        Log::info("Twilio -> Enviando para $to: $message com API Key: $this->apiKey");
        return true;
    }
}
