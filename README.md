# 📬 MultiMessenger

**MultiMessenger** é um sistema de envio de mensagens desacoplado e extensível para Laravel, com suporte a múltiplos canais (drivers), fallback automático, templates, metadata, tags, e reprocessamento de falhas.

## 🚀 Recursos

- ✅ Suporte a múltiplos drivers: `Postmark`, `WhatsApp`, `Mock`, etc.
- ✅ Fallback automático entre drivers em caso de falha
- ✅ Envio de e-mails com:
    - Texto puro (`TextBody`)
    - HTML (`HtmlBody`)
    - Templates (`TemplateId`, `TemplateModel`)
- ✅ Suporte a Tags e Metadata
- ✅ Registro de falhas no banco de dados
- ✅ Reprocessamento automático/manual de mensagens falhadas

---

## 🏗️ Estrutura

- `App\Contracts\MessageServiceInterface`: contrato para todos os drivers
- `App\Services\*MessageService`: implementações específicas (Postmark, WhatsApp, etc.)
- `App\Services\FailoverMessageService`: tenta múltiplos drivers em sequência
- `App\Models\MessageFailure`: registros de falhas
- `App\Console\Commands\ReprocessFailedMessages`: comando para reprocessar falhas

---

## ⚙️ Instalação

1. Clone o projeto ou copie os arquivos necessários.
2. Adicione ao `.env`:

```env
MESSAGE_DRIVER=failover
POSTMARK_TOKEN=your-postmark-token
POSTMARK_FROM=your-email@example.com
WHATSAPP_TOKEN=whatsapp-fake-token
WHATSAPP_FROM=whatsapp:+1234567890
```

3. Adicione em config/services.php:

```php
'message' => [
    'driver' => env('MESSAGE_DRIVER', 'mock'),
    'api_key' => env('MESSAGE_API_KEY'),
    'whatsapp_token' => env('WHATSAPP_TOKEN'),
    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
        'from' => env('POSTMARK_FROM'),
    ],
],
```
4. Execute as migrations:
```bash
php artisan migrate
```

## 🔁 Reprocessando falhas

Para reprocessar mensagens falhadas, execute o comando:

```bash
php artisan messages:reprocess
```
Remove da base as mensagens que forem reenviadas com sucesso.

## 📊 Logs de Falhas
As mensagens que falharem são salvas na tabela message_failures, contendo:

* Destinatário (to)

* Mensagem

* Driver

* Motivo da falha

* Dados adicionais (options)

* Status

## 📦 Extensível
Você pode adicionar drivers personalizados com facilidade, bastando implementar MessageServiceInterface.

## 🧪 Testes
Mock facilmente o envio em testes:

```php
$this->app->instance(MessageServiceInterface::class, new MockMessageService());
```





