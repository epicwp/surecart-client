<?php

namespace SureCart\Message;

use Psr\Http\Message\ResponseInterface;

class ResponseMediator
{
    public static function getContent(ResponseInterface $response)
    {
        $body = $response->getBody()->__toString();

        if (\str_starts_with($response->getHeaderLine('Content-Type'), 'application/json')) {
            $content = \json_decode($body, true);

            if (JSON_ERROR_NONE === \json_last_error()) {
                return $content;
            }
        }

        return $body;
    }
}
