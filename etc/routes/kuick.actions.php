<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\Http\RequestMethod;
use Kuick\Security\Ops\OpsGuard;
use Kuick\UI\Example\HelloAction;
use Kuick\UI\Ops\OpsAction;

return [
    //You probably need to remove/replace this route
    [
        'pattern' => '/',
        'method' => RequestMethod::GET, //optional
        'action' => HelloAction::class,
    ],
    [
        'pattern' => '/api/ops',
        //'method' => RequestMethod::GET, //optional
        'action' => OpsAction::class,
        'guards' => [OpsGuard::class]
    ],
];
