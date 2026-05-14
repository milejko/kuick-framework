<?php

namespace Tests\Unit\Kuick\Framework\Listeners;

use Kuick\EventDispatcher\EventDispatcher;
use Kuick\EventDispatcher\ListenerProvider;
use Kuick\Framework\Events\ResponseCreatedEvent;
use Kuick\Framework\Listeners\ResponseEmittingListener;
use Kuick\Http\Message\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

#[CoversClass(ResponseEmittingListener::class)]
class ResponseEmittingListenerTest extends TestCase
{
    public function testIfResponseIsEmitted(): void
    {
        $responseCreatedEvent = new ResponseCreatedEvent(new Response(200, [], 'Hello world!'));
        ob_start();
        $listenerProvider = new ListenerProvider();
        $eventDispatcher = new EventDispatcher($listenerProvider);
        (new ResponseEmittingListener($eventDispatcher, new NullLogger()))($responseCreatedEvent);
        $this->assertEquals('Hello world!', ob_get_clean());
    }
}
