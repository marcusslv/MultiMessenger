<?php

namespace App\Jobs;

use App\Contracts\MessageServiceInterface;
use App\Models\MessageFailure;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\App;
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

    public function handle()
    {
        $channel = $this->options['_channel'] ?? 'default';

        /** @var array<string, MessageServiceInterface> $drivers */
        $drivers = App::make('message.drivers');

        $driver = $drivers[$channel] ?? null;

        if (!$driver) {
            throw new \Exception("Driver [$channel] não encontrado");
        }

        if (!$driver->send($this->to, $this->message, $this->options)) {
            throw new \Exception("Envio falhou via canal [$channel]");
        }

        Log::info("✔ [$channel] Envio via fila concluído para {$this->to}");
    }

    public function failed(\Throwable $exception)
    {
        $channel = $this->options['_channel'] ?? 'desconhecido';

        MessageFailure::create([
            'to' => $this->to,
            'message' => $this->message,
            'driver' => $channel,
            'error' => $exception->getMessage(),
            'options' => $this->options,
            'status' => 'failed',
        ]);

        Log::error("✘ [$channel] Falhou após retries: " . $exception->getMessage());
    }
}
