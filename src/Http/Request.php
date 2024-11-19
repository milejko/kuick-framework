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
class Request
{
    private string $method;
    private string $uri;
    private string $path;
    private string $body;
    private array $headers = [];
    private array $queryParams = [];

    public function withMethod(string $method): self
    {
        if (!in_array($method, HttpMethod::ALL_METHODS)) {
            throw new BadRequestException('Method invalid');
        }
        $this->method = $method;
        return $this;
    }

    public function withUri(string $uri): self
    {
        $this->uri = $uri;
        $parsedUrl = parse_url($this->uri);
        $queryParams = [];
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
        }
        $this->queryParams = is_array($queryParams) ? $queryParams : [];
        $this->path = isset($parsedUrl['path']) ? ($parsedUrl['path'] == '/' ? $parsedUrl['path'] : rtrim($parsedUrl['path'], '/')) : '';
        return $this;
    }

    public function withBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function withHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function withQueryParam(string $name, string $value): self
    {
        $this->queryParams[$name] = $value;
        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getBodyAsArray(): array
    {
        $decodedBody = json_decode($this->getBody(), true);
        if (null === $decodedBody) {
            throw new BadRequestException('Request body is not a valid JSON');
        }
        return $decodedBody;
    }

    public function getHeaders(): array
    {
        $headers = [];
        foreach ($this->getHeaders() as $headerName => $headerValue) {
            $headers[] = $headerName . ': ' . $headerValue;
        }
        return $headers;
    }

    public function getHeader(string $name): string
    {
        foreach ($this->headers as $headerName => $value) {
            if (strtolower($name) == strtolower($headerName)) {
                return $value;
            }
        }
        return '';
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getQueryParam(string $name): ?string
    {
        return isset($this->queryParams[$name]) ? $this->queryParams[$name] : null;
    }
}
