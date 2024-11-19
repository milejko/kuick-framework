<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\App\Kernel;
use Kuick\Http\RequestFactory;

define('BASE_PATH', __DIR__ . '/../');
require BASE_PATH . '/vendor/autoload.php';

$request = RequestFactory::createRequestWithServerGlobals($_SERVER, file_get_contents('php://input'));
(new Kernel($request))->run();
