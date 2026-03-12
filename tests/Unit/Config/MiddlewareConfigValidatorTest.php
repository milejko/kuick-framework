<?php

namespace Tests\Unit\Kuick\Framework\Config;

use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\Config\MiddlewareConfig;
use Kuick\Framework\Config\MiddlewareConfigValidator;
use Kuick\Routing\RoutingMiddleware;
use Kuick\Security\SecurityMiddleware;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use stdClass;

#[CoversClass(MiddlewareConfigValidator::class)]
class MiddlewareConfigValidatorTest extends TestCase
{
    public function testIfCorrectMiddlewareConfigValidatorDoesNothing(): void
    {
        $middlewareConfig = new MiddlewareConfig(SecurityMiddleware::class, RoutingMiddleware::class);
        (new MiddlewareConfigValidator())->validate($middlewareConfig);
        $this->expectNotToPerformAssertions();
    }

    public function testIfCorrectMiddlewareWithoutBeforeMiddlewareConfigValidatorDoesNothing(): void
    {
        $middlewareConfig = new MiddlewareConfig(SecurityMiddleware::class);
        (new MiddlewareConfigValidator())->validate($middlewareConfig);
        $this->expectNotToPerformAssertions();
    }

    public function testIfEmptyMiddlewareClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Middleware class name should not be empty');
        (new MiddlewareConfigValidator())->validate(new MiddlewareConfig(''));
    }

    public function testIfInexistentMiddlewareClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Middleware class: InexistentMiddleware does not exist');
        (new MiddlewareConfigValidator())->validate(new MiddlewareConfig('InexistentMiddleware'));
    }

    public function testIfNotMiddlewareRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Middleware does not implement MiddlewareInterface: stdClass');
        (new MiddlewareConfigValidator())->validate(new MiddlewareConfig(stdClass::class));
    }

    public function testIfInexistentBeforeMiddlewareClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Before middleware class: InexistentBeforeMiddleware does not exist');
        (new MiddlewareConfigValidator())->validate(new MiddlewareConfig(SecurityMiddleware::class, 'InexistentBeforeMiddleware'));
    }

    public function testIfNotBeforeMiddlewareRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Before middleware does not implement MiddlewareInterface: stdClass');
        (new MiddlewareConfigValidator())->validate(new MiddlewareConfig(SecurityMiddleware::class, stdClass::class));
    }
}
