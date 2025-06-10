<?php

namespace App\Jobs;

use App\Contracts\MessageServiceInterface;
use App\Models\MessageFailure;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $to;
    public string $message;
    public array $options;

    public int $tries = 3;
    public int $backoff = 10;

    public function __construct(string $to, string $message, array $options = [])
    {
        $this->to = $to;
        $this->message = $message;
        $this->options = $options;
    }

    public function handle(MessageServiceInterface $service)
    {
        if (!$service->send($this->to, $this->message, $this->options)) {
            throw new \Exception("Envio falhou para {$this->to}");
        }

        Log::info("Mensagem enviada com sucesso via fila: {$this->to}");
    }

    public function failed(\Throwable $exception)
    {
        Log::error("Job falhou após todas tentativas: {$exception->getMessage()}");

        MessageFailure::create([
            'to' => $this->to,
            'message' => $this->message,
            'driver' => 'queued',
            'error' => $exception->getMessage(),
            'options' => $this->options,
            'status' => 'failed',
        ]);
    }
}

