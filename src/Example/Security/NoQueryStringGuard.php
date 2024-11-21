<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Example\Security;

use Kuick\Http\BadRequestException;
use Kuick\Http\Request;
use Kuick\Security\GuardInterface;

class NoQueryStringGuard implements GuardInterface
{
    public function __invoke(Request $request): void
    {
        if (!empty($request->query->all())) {
            throw new BadRequestException('Query string should be empty for this page');
        }
    }
}
