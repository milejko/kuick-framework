<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Ops\Security;

use DI\Attribute\Inject;
use Kuick\Http\JsonErrorResponse;
use Kuick\Http\Request;
use Kuick\Security\GuardInterface;

class OpsGuard implements GuardInterface
{
    private const AUTHORIZATION_HEADER = 'Authorization';
    private const BEARER_TOKEN_TEMPLATE = 'Bearer %s';
    
    public function __construct(#[Inject('kuick.ops.guards.token')] private string $opsToken) {}

    public function __invoke(Request $request): ?JsonErrorResponse
    {
        $requestToken = $request->headers->get(self::AUTHORIZATION_HEADER);
        if (null === $requestToken) {
            return new JsonErrorResponse('Token is missing');
        }
        $expectedToken = sprintf(self::BEARER_TOKEN_TEMPLATE, $this->opsToken);
        //token mismatch
        if ($requestToken != $expectedToken) {
            return new JsonErrorResponse('Token is invalid');
        }
    }
}
