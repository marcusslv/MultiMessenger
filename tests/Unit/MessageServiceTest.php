<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Contracts\MessageServiceInterface;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageServiceTest extends TestCase
{
    public function test_message_send_successfully()
    {
        $mock = \Mockery::mock(MessageServiceInterface::class);
        $mock->shouldReceive('send')
            ->once()
            ->with('+5511988887777', 'Teste mockado')
            ->andReturn(true);

        $this->app->instance(MessageServiceInterface::class, $mock);

        $service = app(MessageServiceInterface::class);
        $result = $service->send('+5511988887777', 'Teste mockado');

        $this->assertTrue($result);
    }
}
