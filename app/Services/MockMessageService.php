<?php

namespace App\Services;

use App\Contracts\MessageServiceInterface;

class MockMessageService implements MessageServiceInterface
{
    public function send(string $to, string $message, array $options = []): bool
    {
        return true;
    }
}
