<?php

namespace Tests\Unit\Kuick\Framework\Config;

use Kuick\Framework\Config\ListenerConfig;
use Kuick\EventDispatcher\ListenerPriority;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Unit\Kuick\Framework\Mocks\MockListener;
use PHPUnit\Framework\TestCase;

#[CoversClass(ListenerConfig::class)]
class ListenerConfigTest extends TestCase
{
    public function testIfListenerConfigIsDefinedWithTheDefaultMethods(): void
    {
        $listenerConfig = new ListenerConfig('*', MockListener::class);
        $this->assertEquals('*', $listenerConfig->pattern);
        $this->assertEquals(MockListener::class, $listenerConfig->listenerClassName);
        $this->assertEquals(0, $listenerConfig->priority);
        $anotherConfig = new ListenerConfig('*', MockListener::class, ListenerPriority::HIGH);
        $this->assertEquals(ListenerPriority::HIGH, $anotherConfig->priority);
    }
}
