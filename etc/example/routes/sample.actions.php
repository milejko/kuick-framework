<?php

use Kuick\Example\Security\NoQueryStringGuard;
use Kuick\Example\UI\HelloAction;
use Kuick\Http\Request;

return [
    [
        'pattern' => '/', # regular expression ie. /api/v[0-9]{1}/[a-z]+
        //'method' => Request::METHOD_GET, #optional for GET
        'action' => HelloAction::class,
        'guards' => [NoQueryStringGuard::class],
    ],
];
