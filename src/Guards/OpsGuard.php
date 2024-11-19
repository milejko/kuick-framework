<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Guards;

use Kuick\Http\HttpForbiddenException;
use Kuick\Http\HttpUnauthorizedException;
use Kuick\Http\Request;
use Kuick\UI\GuardInterface;

class OpsGuard implements GuardInterface
{
    private const OPS_TOKEN = 'test';

    public function __invoke(Request $request): void
    {
        if (!$request->getQueryParam('token')) {
            throw new HttpUnauthorizedException('Token is missing');
        }
        if ($request->getQueryParam('token') != self::OPS_TOKEN) {
            throw new HttpForbiddenException('Token invalid');
        }
    }
}
