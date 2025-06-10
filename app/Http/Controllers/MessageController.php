<?php

namespace App\Http\Controllers;

use App\Contracts\MessageServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected MessageServiceInterface $messageService;

    public function __construct(MessageServiceInterface $messageService)
    {
        $this->messageService = $messageService;
    }

    public function send(): JsonResponse
    {
        $this->messageService->send(
            'marcusviniciusdasilva6@gmail.com',
            'Corpo de texto puro',
            [
                'subject' => 'Assunto do Email',
                'html' => '<h1>Olá!</h1><p>Seja bem-vindo.</p>',
                'tag' => 'html-email',
                'metadata' => ['user_id' => 123]
            ]
        );

        return response()->json(['status' => 'Mensagem enviada com interface!']);
    }
}
