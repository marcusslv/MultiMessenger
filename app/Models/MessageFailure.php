<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageFailure extends Model
{
    protected $fillable = [
        'to',
        'message',
        'driver',
        'error',
        'options',
        'status',
    ];

    protected $casts = [
        'options' => 'array',
    ];
}
