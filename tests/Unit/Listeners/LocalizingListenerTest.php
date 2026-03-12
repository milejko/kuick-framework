<?php

namespace Tests\Unit\Kuick\Framework\Listeners;

use Kuick\Framework\Listeners\LocalizingListener;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

#[CoversClass(LocalizingListener::class)]
class LocalizingListenerTest extends TestCase
{
    public function testIfLocalizationIsSet(): void
    {
        (new LocalizingListener('pl_PL.utf-8', 'Europe/Warsaw', 'UTF-8', new NullLogger()))();
        $this->assertEquals('UTF-8', ini_get('default_charset'));
        $this->assertEquals('Europe/Warsaw', ini_get('date.timezone'));
        $this->assertEquals('Europe/Warsaw', date_default_timezone_get());
        $this->assertEquals('UTF-8', mb_internal_encoding());
    }
}
