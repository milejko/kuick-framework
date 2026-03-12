<?php

use Kuick\Framework\Config\MiddlewareConfig;
use Kuick\Framework\OptionsServingMiddleware;
use Kuick\Routing\RoutingMiddleware;
use Kuick\Security\SecurityMiddleware;

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

return [
    // middleware for security (guards)
    new MiddlewareConfig(SecurityMiddleware::class),
    // middleware for serving OPTIONS requests
    new MiddlewareConfig(OptionsServingMiddleware::class),
    // middleware for routing
    new MiddlewareConfig(RoutingMiddleware::class),
];