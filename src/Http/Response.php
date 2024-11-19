<?php

/**
 * Message Broker
 *
 * @link       https://github.com/milejko/message-broker.git
 * @copyright  Copyright (c) 2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http;

/**
 *
 */
class Response
{
    public const CODE_OK = 200;
    public const CODE_ACCEPTED = 202;
    public const CODE_NO_CONTENT = 204;
    
    public const CODE_MOVED_PERMANENTLY = 301;

    public const CODE_BAD_REQUEST = 400;
    public const CODE_UNAUTHORIZED = 401;
    public const CODE_FORBIDDEN = 403;
    public const CODE_NOT_FOUND = 404;

    public const CODE_ERROR = 500;

    private array $headers = [];
    private string $body = '';

    public function withHeader(string $name, string $value, int $code = null): self
    {
        $validCodes = [
            self::CODE_ACCEPTED,
            self::CODE_BAD_REQUEST,
            self::CODE_ERROR,
            self::CODE_FORBIDDEN,
            self::CODE_MOVED_PERMANENTLY,
            self::CODE_NO_CONTENT,
            self::CODE_NOT_FOUND,
            self::CODE_OK,
            self::CODE_UNAUTHORIZED,
        ];
        if ($code && !in_array($code, $validCodes)) {
            throw new ResponseException('Code invalid');
        }
        $header = [
            'name' => $name,
            'value' => $value,
            'code' => $code
        ];
        $this->headers[] = $header;
        return $this;
    }

    public function withBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }
    
    public function getBody(): string
    {
        return $this->body;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function sendHeaders(): void
    {
        foreach ($this->getHeaders() as $header) {
            $headerLine = $header['name'] . ': ' . $header['value'];
            $header['code'] ?
                header($headerLine, true, $header['code']) :
                header($headerLine, true);
        }
    }

    public function sendBody(): void
    {
        echo $this->body;
    }

    public function send(): void
    {
        $this->sendHeaders();
        $this->sendBody();
    }
}
