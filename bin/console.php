#!/usr/bin/env php
<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\App\CommandKernel;

define('BASE_PATH', __DIR__ . '/../');

require BASE_PATH . '/vendor/autoload.php';

ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');

(new CommandKernel())($argv);
