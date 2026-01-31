<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

use Kuick\Framework\WebKernel;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

$projectDir = dirname(__DIR__);
require "$projectDir /vendor/autoload.php";

// Using .env loader is not recommended from the performance perspective
// uncomment the line below if you really want to use it
// Kuick\Dotenv\DotEnvLoader::fromDirectory($projectDir);

$psr17Factory = new Psr17Factory();

$request = (new ServerRequestCreator(
    serverRequestFactory: $psr17Factory,
    uriFactory: $psr17Factory,
    uploadedFileFactory: $psr17Factory,
    streamFactory: $psr17Factory,
))->fromGlobals();

(new WebKernel($projectDir))->run($request);
