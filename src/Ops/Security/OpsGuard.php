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
use Kuick\Http\HttpForbiddenException;
use Kuick\Security\GuardInterface;
use Symfony\Component\HttpFoundation\Request;

class OpsGuard implements GuardInterface
{
    public const TOKEN_CONFIG_KEY = 'kuick_guards_ops_token';

    private const AUTHORIZATION_HEADER = 'Authorization';
    private const BEARER_TOKEN_TEMPLATE = 'Bearer %s';
    private const MESSAGE_TOKEN_MISMATCH = 'Token is missing or invalid';

    public function __construct(private AppConfig $appConfig) {}

    public function __invoke(Request $request): void
    {
        //token mismatch
        if ($request->headers->get(self::AUTHORIZATION_HEADER) != sprintf(self::BEARER_TOKEN_TEMPLATE, $this->appConfig->get(self::TOKEN_CONFIG_KEY))) {
            throw new HttpForbiddenException(self::MESSAGE_TOKEN_MISMATCH);
        }
    }
}
