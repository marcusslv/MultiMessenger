<?php

namespace App\Console\Commands;

use App\Contracts\MessageServiceInterface;
use App\Models\MessageFailure;
use Illuminate\Console\Command;

class ReprocessFailedMessages extends Command
{
    protected $signature = 'messages:reprocess';

    protected $description = 'Tenta reenviar mensagens que falharam anteriormente';

    public function handle(MessageServiceInterface $messageService)
    {
        $failures = MessageFailure::where('status', 'failed')->get();
        $this->info("Reprocessando {$failures->count()} mensagens...");

        foreach ($failures as $failure) {
            $this->info("Tentando reenviar para {$failure->to} via fallback...");

            $success = $messageService->send(
                $failure->to,
                $failure->message,
                $failure->options ?? []
            );

            if ($success) {
                $failure->delete();
                $this->info("✔ Enviado com sucesso e removido do histórico.");
            } else {
                $failure->error = 'Reprocessamento falhou novamente';
                $failure->save();
                $this->warn("✘ Falha no reenvio.");
            }
        }

        $this->info("Reprocessamento concluído.");
        return Command::SUCCESS;
    }
}
