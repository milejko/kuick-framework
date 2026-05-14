<?php

namespace Tests\Unit\Kuick\Framework;

use Kuick\Framework\OptionsServingMiddleware;
use Kuick\Http\Message\Response;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Kuick\Framework\Mocks\MockRequestHandler;

#[CoversClass(OptionsServingMiddleware::class)]
class OptionsServingMiddlewareTest extends TestCase
{
    public function testIfNonOptionsRequestIsPassedToHandler(): void
    {
        $middleware = new OptionsServingMiddleware();
        $request = new ServerRequest('GET', '/test');
        $response = $middleware->process($request, new MockRequestHandler());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testIfOptionsRequestReturnsNoContent(): void
    {
        $middleware = new OptionsServingMiddleware();
        $request = new ServerRequest('OPTIONS', '/test');
        $response = $middleware->process($request, new MockRequestHandler());
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
