<?php

namespace Tests\Unit\Kuick\Framework\Listeners;

use Kuick\Framework\Events\RequestReceivedEvent;
use Kuick\Framework\Events\ResponseCreatedEvent;
use Kuick\Framework\Listeners\RequestHandlingListener;
use Kuick\EventDispatcher\EventDispatcher;
use Kuick\EventDispatcher\ListenerProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Unit\Kuick\Framework\Mocks\MockRequestHandler;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

#[CoversClass(RequestHandlingListener::class)]
class RequestHandlingListenerTest extends TestCase
{
    public function testIfRequestIsHandled(): void
    {
        $listenerProvider = new ListenerProvider();
        $listenerProvider->registerListener(ResponseCreatedEvent::class, function (ResponseCreatedEvent $event) use (&$responseCreated) {
            $responseCreated = $event->getResponse();
        });
        $requestHandling = new RequestHandlingListener(
            new MockRequestHandler(),
            new EventDispatcher($listenerProvider),
            new NullLogger()
        );

        $requestReceivedEvent = new RequestReceivedEvent(new ServerRequest('GET', '/test'));
        $requestHandling($requestReceivedEvent);
        $this->assertEquals(200, $responseCreated->getStatusCode());
    }
}
