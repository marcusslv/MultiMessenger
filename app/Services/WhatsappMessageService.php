<?php

namespace App\Services;

use App\Contracts\MessageServiceInterface;
use Illuminate\Support\Facades\Log;

class WhatsappMessageService implements MessageServiceInterface
{
    protected string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function send(string $to, string $message, array $options = []): bool
    {
        Log::info("WhatsApp -> Enviando para $to: $message usando token: $this->token");

        return true;
    }
}
