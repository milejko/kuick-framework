<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\UI;

use Kuick\Http\JsonResponse;
use Kuick\Http\Request;

class DefaultRootAction implements ActionInterface
{
    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse(['Kuick says: hello!']);
    }
}