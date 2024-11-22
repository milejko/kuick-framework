<?php

namespace Tests\Kuick\App;

use Kuick\App\ApplicationException;
use Kuick\App\DotEnvParser;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\App\DotEnvParser
 */
class HelloActionTest extends TestCase
{
    public function testIfInexistentFileProducesException()
    {
        $this->expectException(ApplicationException::class);
        (new DotEnvParser)(BASE_PATH . '/tests/inexisten.env');
    }
    public function testIfDotEnvFileIsParsedCorrectly()
    {
        $values = (new DotEnvParser)(BASE_PATH . '/tests/Mocks/.env.sample');
        self::assertEquals([
            'some.key' => 'somevalue',
            'other.key' => 'other value now with spaces',
            'empty.line.below' => 'see below',
            'class.format' => '\Some\ClassName',
            'dots' => 'having.some.dots',
            'empty.value' => '',
            'int.value' => '315',
        ], $values);
    }
}