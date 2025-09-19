<?php

use Kuick\Framework\Config\MiddlewareConfig;
use Kuick\Routing\RoutingMiddleware;
use Kuick\Security\SecurityMiddleware;

return [
    // security middleware should be the first
    new MiddlewareConfig(SecurityMiddleware::class),
    // routing middleware should be the last
    new MiddlewareConfig(RoutingMiddleware::class),
];
