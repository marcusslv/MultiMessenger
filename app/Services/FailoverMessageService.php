<?php

namespace App\Services;


use App\Contracts\MessageServiceInterface;
use App\Models\MessageFailure;
use Illuminate\Support\Facades\Log;

class FailoverMessageService implements MessageServiceInterface
{
    /** @var MessageServiceInterface[] */
    protected array $drivers;

    public function __construct(array $drivers)
    {
        $this->drivers = $drivers;
    }

    public function send(string $to, string $message, array $options = []): bool
    {
        Log::info('Iniciando envio de mensagem para: ' . $to);
        foreach ($this->drivers as $driver) {
            try {
                if ($driver->send($to, $message, $options)) {
                    Log::info('Envio bem-sucedido com driver: ' . get_class($driver));
                    return true;
                }
            } catch (\Throwable $e) {
                Log::warning("Driver " . get_class($driver) . " falhou: " . $e->getMessage());

                MessageFailure::create([
                    'to' => $to,
                    'message' => $message,
                    'driver' => get_class($driver),
                    'error' => $e->getMessage(),
                    'options' => $options,
                    'status' => 'failed',
                ]);
            }
        }

        Log::error('Todos os drivers falharam ao tentar enviar mensagem.');
        return false;
    }
}
