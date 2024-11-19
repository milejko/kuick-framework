<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\UI;

use Kuick\App\KernelConfig;
use Kuick\Http\HttpForbiddenException;
use Kuick\Http\HttpUnauthorizedException;
use Kuick\Http\Request;
use Kuick\UI\GuardInterface;

class OpsGuard implements GuardInterface
{
    private const AUTHORIZATION_HEADER = 'Authorization';
    private const OPS_TOKEN_PREFIX = 'Bearer ';

    public function __construct(private KernelConfig $kernelConfig)
    {
        
    }

    public function __invoke(Request $request): void
    {
        if (!$request->getHeader(self::AUTHORIZATION_HEADER)) {
            throw new HttpUnauthorizedException('Token is missing');
        }
        $expectedToken = self::OPS_TOKEN_PREFIX . $this->kernelConfig->get('opsToken');
        if ($request->getHeader(self::AUTHORIZATION_HEADER) != $expectedToken) {
            throw new HttpForbiddenException('Token invalid');
        }
    }
}
