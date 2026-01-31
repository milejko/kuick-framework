<?php

namespace Tests\Unit\Kuick\Framework\Events;

use Exception;
use Kuick\Framework\Events\ExceptionRaisedEvent;
use Monolog\Test\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ExceptionRaisedEvent::class)]
class ExceptionRaisedEventTest extends TestCase
{
    public function testIfResponseCanBeRetrievedFromTheEvent(): void
    {
        $event = new ExceptionRaisedEvent(new Exception('test'));
        $this->assertEquals('test', $event->getException()->getMessage());
    }
}
