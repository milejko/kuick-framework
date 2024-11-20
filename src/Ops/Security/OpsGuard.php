<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Ops\Security;

use Kuick\App\AppConfig;
use Kuick\Security\GuardInterface;
use Kuick\UI\UIForbiddenException;
use Kuick\UI\UIUnauthorizedException;
use Symfony\Component\HttpFoundation\Request;

class OpsGuard implements GuardInterface
{
    public const TOKEN_CONFIG_KEY = 'kuick_guards_ops_token';

    private const AUTHORIZATION_HEADER = 'Authorization';
    private const BEARER_TOKEN_TEMPLATE = 'Bearer %s';
    private const ERROR_MISSING_TOKEN = 'Token is missing';
    private const ERROR_INVALID_TOKEN = 'Token is invalid';

    public function __construct(private AppConfig $appConfig) {}

    public function __invoke(Request $request): void
    {
        $requestToken = $request->headers->get(self::AUTHORIZATION_HEADER);
        if (null === $requestToken) {
            throw new UIUnauthorizedException(self::ERROR_MISSING_TOKEN);
        }
        $expectedToken = sprintf(self::BEARER_TOKEN_TEMPLATE, $this->appConfig->get(self::TOKEN_CONFIG_KEY));
        //token mismatch
        if ($requestToken != $expectedToken) {
            throw new UIForbiddenException(self::ERROR_INVALID_TOKEN);
        }
    }
}
