<?php

namespace App\Services;

use App\Contracts\MessageServiceInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PostmarkEmailService implements MessageServiceInterface
{
    protected string $token;
    protected string $fromEmail;

    public function __construct(string $token, string $fromEmail)
    {
        $this->token = $token;
        $this->fromEmail = $fromEmail;
    }

    /**
     * @throws ConnectionException
     */
    public function send(string $to, string $message, array $options = []): bool
    {
        logger("Postmark -> Enviando email para $to: $message");
        return false;

        $subject = $options['subject'] ?? 'Mensagem via Postmark';
        $tag = $options['tag'] ?? null;
        $metadata = $options['metadata'] ?? [];

        $templateId = $options['template_id'] ?? null;
        $templateModel = $options['template_model'] ?? [];

        if ($templateId) {
            // Envio usando template
            $payload = [
                'From' => $this->fromEmail,
                'To' => $to,
                'TemplateId' => $templateId,
                'TemplateModel' => $templateModel,
            ];
        } else {
            // Envio tradicional
            $payload = [
                'From' => $this->fromEmail,
                'To' => $to,
                'Subject' => $subject,
                'TextBody' => $message,
                'HtmlBody' => $options['html'] ?? null,
            ];
        }

        if ($tag) {
            $payload['Tag'] = $tag;
        }

        if (!empty($metadata)) {
            $payload['Metadata'] = $metadata;
        }

        $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-Postmark-Server-Token' => $this->token,
            ])->post('https://api.postmarkapp.com/email', $payload);

        if ($response->successful()) {
            Log::info("Email enviado para $to com " . ($templateId ? "template [$templateId]" : "assunto [$subject]"));
            return true;
        }

        Log::error("Erro ao enviar email: " . $response->body());
        throw new \Exception("Erro ao enviar email: " . $response->body());
    }
}
