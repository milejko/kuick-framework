<?php

use Kuick\App\Kernel;
use Kuick\Http\RequestFactory;

define('BASE_PATH', __DIR__ . '/../');
require BASE_PATH . '/vendor/autoload.php';

$request = RequestFactory::createRequestWithServerGlobals($_SERVER, file_get_contents('php://input'));
(new Kernel($request))->run();
