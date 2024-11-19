<?php

namespace Kuick\Http;

class HttpClient
{
    public function query(Request $request): array
    {
        return json_decode(
            file_get_contents(
                $request->getUri(),
                false,
                stream_context_create([
                    'http' => [
                        'content' => $request->getBody(),
                        'method' => $request->getMethod(),
                        'header' => implode("\r\n", $request->getHeaders())
                    ]
                ])
            ),
            true,
            flags: JSON_THROW_ON_ERROR
        );
    }
}
