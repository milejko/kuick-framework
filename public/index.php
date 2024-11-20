<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

define('BASE_PATH', realpath(dirname(__DIR__)));
require BASE_PATH . '/vendor/autoload.php';

(new Kuick\App\WebApplication)(
    Kuick\Http\RequestFactory::create($_SERVER, file_get_contents('php://input'))
);
