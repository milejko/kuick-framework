<?php

namespace Tests\Unit\Kuick\Framework\Events;

use Kuick\Framework\Events\KernelCreatedEvent;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Unit\Kuick\Framework\Mocks\MockKernel;
use Monolog\Test\TestCase;

#[CoversClass(KernelCreatedEvent::class)]
class KernelCreatedEventTest extends TestCase
{
    public function testIfKernelObjectCanBeRetrievedFromTheEvent(): void
    {
        $kernel = new MockKernel();
        $event = new KernelCreatedEvent($kernel);
        $this->assertEquals($kernel, $event->getKernel());
    }
}
