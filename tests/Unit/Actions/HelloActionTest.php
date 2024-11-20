<?php

namespace Tests\Kuick\Actions;

use Kuick\Http\RequestFactory;
use Kuick\UI\Example\HelloAction;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\UI\Example\HelloAction
 */
class HelloActionTest extends TestCase
{
    public function testIfKuickSaysHello(): void
    {
        $request = RequestFactory::create([]);
        $response = (new HelloAction())($request);
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
