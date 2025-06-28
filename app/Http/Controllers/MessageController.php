<?php

namespace App\Http\Controllers;

use App\Contracts\MessageServiceInterface;
use App\Jobs\SendMessageJob;
use App\Services\MultiChannelMessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected $messageService;

    public function __construct(MessageServiceInterface $messageService)
    {
        $this->messageService = $messageService;
    }

    public function send(Request $request): JsonResponse
    {
        $email = $request->get('email', 'marcusviniciusdasilva6@gmail.com');
        $this->messageService->send(
            $email,
            'Mensagem multicanal via fila',
            [
                'subject' => 'Fila Multicanal',
                'html' => '<p>Conteúdo HTML</p>',
                'tag' => 'multicanal-fila',
                'metadata' => ['user_id' => 987]
            ]
        );

        return response()->json(['status' => 'Mensagem enviada com interface!']);
    }
}
