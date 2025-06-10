<?php

namespace App\Http\Controllers;

use App\Contracts\MessageServiceInterface;
use App\Jobs\SendMessageJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function send(): JsonResponse
    {
        SendMessageJob::dispatch(
            'marcusviniciusdasilva6@gmail.com',
            'Corpo de texto puro com fila',
            [
                'subject' => 'Assunto Fila',
                'html' => '<p>Enviado com queue!</p>',
                'tag' => 'queue-email',
                'metadata' => ['user_id' => 99]
            ]
        );

        return response()->json(['status' => 'Mensagem enviada com interface!']);
    }
}
