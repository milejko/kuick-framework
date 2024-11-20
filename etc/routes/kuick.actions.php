<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\Example\UI\HelloAction;
use Kuick\Ops\Security\OpsGuard;
use Kuick\Ops\UI\OpsAction;
use Symfony\Component\HttpFoundation\Request;

return [
    //You probably need to remove/replace this route
    [
        'pattern' => '/',
        'method' => Request::METHOD_GET, //optional
        'action' => HelloAction::class,
    ],
    [
        'pattern' => '/api/ops',
        //'method' => Request::METHOD_GET, //optional
        'action' => OpsAction::class,
        'guards' => [OpsGuard::class]
    ],
];
