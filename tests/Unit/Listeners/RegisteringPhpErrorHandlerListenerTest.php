<?php

namespace Tests\Unit\Kuick\Framework\Listeners;

use Kuick\EventDispatcher\EventDispatcher;
use Kuick\EventDispatcher\ListenerProvider;
use Kuick\Framework\Listeners\RegisteringPhpErrorHandlerListener;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

#[CoversClass(RegisteringPhpErrorHandlerListener::class)]
class RegisteringPhpErrorHandlerListenerTest extends TestCase
{
    public function testIfErrorHandlerIsRegistered(): void
    {
        (new RegisteringPhpErrorHandlerListener(new EventDispatcher(new ListenerProvider()), new NullLogger()))();
        $this->expectNotToPerformAssertions();
        restore_error_handler();
        restore_exception_handler();
    }
}
