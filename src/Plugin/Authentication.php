<?php

namespace SureCart\Plugin;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;

final class Authentication implements Plugin
{
    public function __construct(private string $apiKey)
    {
    }

    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        $request = $request->withHeader('authorization', 'Bearer ' . $this->apiKey);

        return $next($request);
    }
}
