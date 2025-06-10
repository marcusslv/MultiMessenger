<?php

namespace App\Contracts;

interface MessageServiceInterface
{
    public function send(string $to, string $message, array $options = []): bool;
}
