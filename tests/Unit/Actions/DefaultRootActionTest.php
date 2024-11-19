<?php

namespace Tests\Kuick\Actions;

use Kuick\Actions\DefaultRootAction;
use Kuick\Http\RequestFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\Actions\DefaultRootAction
 */
class DefaultRootActionTest extends TestCase
{
    public function testIfKuickSaysHello(): void
    {
        $request = RequestFactory::createRequestWithServerGlobals([]);
        $response = (new DefaultRootAction)($request);
        self::assertEquals('["Kuick says: hello!"]', $response->getBody());
        self::assertEquals([
            [
                'name' => 'Content-type',
                'value' => 'application/json',
                'code' => 200
            ]
        ], $response->getHeaders());
    }
}
