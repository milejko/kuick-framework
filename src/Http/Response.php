<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Response extends SymfonyResponse {

    public const ALL_HTTP_CODES = [
        self::HTTP_ACCEPTED,
        self::HTTP_ALREADY_REPORTED,
        self::HTTP_BAD_GATEWAY,
        self::HTTP_BAD_REQUEST,
        self::HTTP_CONFLICT,
        self::HTTP_CONTINUE,
        self::HTTP_CREATED,
        self::HTTP_EARLY_HINTS,
        self::HTTP_EXPECTATION_FAILED,
        self::HTTP_FAILED_DEPENDENCY,
        self::HTTP_FORBIDDEN,
        self::HTTP_FOUND,
        self::HTTP_GATEWAY_TIMEOUT,
        self::HTTP_GONE,
        self::HTTP_I_AM_A_TEAPOT,
        self::HTTP_IM_USED,
        self::HTTP_INSUFFICIENT_STORAGE,
        self::HTTP_INTERNAL_SERVER_ERROR,
        self::HTTP_LENGTH_REQUIRED,
        self::HTTP_LOCKED,
        self::HTTP_LOOP_DETECTED,
        self::HTTP_METHOD_NOT_ALLOWED,
        self::HTTP_MISDIRECTED_REQUEST,
        self::HTTP_MOVED_PERMANENTLY,
        self::HTTP_MULTI_STATUS,
        self::HTTP_MULTIPLE_CHOICES,
        self::HTTP_NETWORK_AUTHENTICATION_REQUIRED,
        self::HTTP_NO_CONTENT,
        self::HTTP_NON_AUTHORITATIVE_INFORMATION,
        self::HTTP_NOT_ACCEPTABLE,
        self::HTTP_NOT_EXTENDED,
        self::HTTP_NOT_FOUND,
        self::HTTP_NOT_IMPLEMENTED,
        self::HTTP_NOT_MODIFIED,
        self::HTTP_OK,
        self::HTTP_PARTIAL_CONTENT,
        self::HTTP_PAYMENT_REQUIRED,
        self::HTTP_PERMANENTLY_REDIRECT,
        self::HTTP_PRECONDITION_FAILED,
        self::HTTP_PRECONDITION_REQUIRED,
        self::HTTP_PROCESSING,
        self::HTTP_PROXY_AUTHENTICATION_REQUIRED,
        self::HTTP_REQUEST_ENTITY_TOO_LARGE,
        self::HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE,
        self::HTTP_REQUEST_TIMEOUT,
        self::HTTP_REQUEST_URI_TOO_LONG,
        self::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE,
        self::HTTP_RESERVED,
        self::HTTP_RESET_CONTENT,
        self::HTTP_SEE_OTHER,
        self::HTTP_SERVICE_UNAVAILABLE,
        self::HTTP_SWITCHING_PROTOCOLS,
        self::HTTP_TEMPORARY_REDIRECT,
        self::HTTP_TOO_EARLY,
        self::HTTP_TOO_MANY_REQUESTS,
        self::HTTP_UNAUTHORIZED,
        self::HTTP_UNAVAILABLE_FOR_LEGAL_REASONS,
        self::HTTP_UNPROCESSABLE_ENTITY,
        self::HTTP_UNSUPPORTED_MEDIA_TYPE,
        self::HTTP_UPGRADE_REQUIRED,
        self::HTTP_USE_PROXY,
    ];
}
