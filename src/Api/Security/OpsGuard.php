<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\Api\Security;

use DI\Attribute\Inject;
use Kuick\Http\HttpException;
use Kuick\Http\Message\JsonResponse;
use OpenApi\Attributes\SecurityScheme;
use Psr\Http\Message\ServerRequestInterface;

#[SecurityScheme(securityScheme: 'Bearer Token', type: 'http', scheme: 'bearer')]
final class OpsGuard
{
    private const string AUTHORIZATION_HEADER = 'Authorization';
    private const string BEARER_TOKEN_TEMPLATE = 'Bearer %s';

    public function __construct(#[Inject('api.security.ops.guard.token')] private string $opsToken)
    {
    }

    public function __invoke(ServerRequestInterface $request): void
    {
        $requestToken = $request->getHeaderLine(self::AUTHORIZATION_HEADER);
        if (!$requestToken) {
            throw new HttpException(JsonResponse::HTTP_UNAUTHORIZED, 'Token not found');
        }
        //request token is invalid
        if ($requestToken != sprintf(self::BEARER_TOKEN_TEMPLATE, $this->opsToken)) {
            throw new HttpException(JsonResponse::HTTP_FORBIDDEN, 'Token invalid');
        }
    }
}
