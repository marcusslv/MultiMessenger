<?php

namespace App\Services;

use App\Contracts\MessageServiceInterface;
use App\Models\MessageFailure;
use Illuminate\Support\Facades\Log;

class MultiChannelMessageService
{
    /** @var array<string, MessageServiceInterface> */
    protected array $channels;

    public function __construct(array $channels)
    {
        $this->channels = $channels;
    }

    public function send(string $to, string $message, array $options = []): void
    {
        foreach ($this->channels as $name => $driver) {
            try {
                $success = $driver->send($to, $message, $options);

                if ($success) {
                    Log::info("✔ [$name] Mensagem enviada para $to");
                } else {
                    throw new \Exception("Falha desconhecida no driver [$name]");
                }
            } catch (\Throwable $e) {
                Log::error("✘ [$name] Falha ao enviar: " . $e->getMessage());

                MessageFailure::create([
                    'to' => $to,
                    'message' => $message,
                    'driver' => $name,
                    'error' => $e->getMessage(),
                    'options' => $options,
                    'status' => 'failed',
                ]);
            }
        }
    }
}
