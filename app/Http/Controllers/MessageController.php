<?php

namespace App\Http\Controllers;

use App\Contracts\MessageServiceInterface;
use App\Jobs\SendMessageJob;
use App\Services\MultiChannelMessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function send(MultiChannelMessageService $multi): JsonResponse
    {
        $multi->send(
            'marcusviniciusdasilva6@gmail.com',
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
