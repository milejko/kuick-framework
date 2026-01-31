<?php

namespace Tests\Unit\Kuick\Framework\Config;

use ErrorException;
use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\Config\RouteConfig;
use Kuick\Framework\Config\RouteConfigValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Unit\Kuick\Framework\Mocks\MockRoute;
use PHPUnit\Framework\TestCase;

#[CoversClass(RouteConfigValidator::class)]
class RouteConfigValidatorTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        // register error handler
        set_error_handler(function ($errno, $errstr, $errfile, $errline): void {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });
    }

    public static function tearDownAfterClass(): void
    {
        // restore previous error handler
        restore_error_handler();
    }

    public function testIfCorrectRouteConfigValidatorDoesNothing(): void
    {
        $routeConfig = new RouteConfig('/test', MockRoute::class);
        (new RouteConfigValidator())->validate($routeConfig);
        $this->expectNotToPerformAssertions();
    }

    public function testIfEmptyPathRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Route path should not be empty');
        (new RouteConfigValidator())->validate(new RouteConfig('', MockRoute::class));
    }

    public function testIfEmptyRouteClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Route controller class name should not be empty');
        (new RouteConfigValidator())->validate(new RouteConfig('/test', ''));
    }

    public function testIfInexistentRouteClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Route controller class: InexistentRoute does not exist');
        (new RouteConfigValidator())->validate(new RouteConfig('/test', 'InexistentRoute'));
    }

    public function testIfNotInvokableRouteClassNameRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Route controller class: stdClass is not invokable');
        (new RouteConfigValidator())->validate(new RouteConfig('/test', 'stdClass'));
    }

    public function testIfInvalidPatternRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Route path should be a valid regex pattern');
        (new RouteConfigValidator())->validate(new RouteConfig('([a-z][[a-z]', MockRoute::class));
    }

    public function testIfInvalidMethodRaisesException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Route method: INVALID is invalid, path: /test');
        (new RouteConfigValidator())->validate(new RouteConfig('/test', MockRoute::class, ['INVALID']));
    }
}
