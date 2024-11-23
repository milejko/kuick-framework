<?php

namespace Tests\Kuick\Example\UI;

use Kuick\Example\UI\HelloAction;
use Kuick\Http\Request;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\UI\Example\HelloAction
 */
class HelloActionTest extends TestCase
{
    public function testIfKuickSaysHello(): void
    {
        $request = new Request();
        $response = (new HelloAction())($request);
        $this->assertEquals('["Kuick says: hello!"]', $response->getContent());
        $this->assertEquals('application/json', $response->headers->get('Content-type'));
        $this->assertEquals(200, $response->getStatusCode());
    }
}
