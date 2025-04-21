<?php

namespace SureCart;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClientFactory;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class Builder
{
    private $client;

    private $plugin_client;

    private $requestFactory;

    private $streamFactory;

    private $clientModified = true;

    private $plugins = array();

    // @phpstan-ignore property.onlyRead
    private $cache;

    private $headers = array();

    public function __construct(
        ClientInterface $client = null,
        RequestFactoryInterface $request_factory = null,
        StreamFactoryInterface $stream_factory = null,
    ) {
        $this->client          = $client ?? Psr18ClientDiscovery::find();
        $this->requestFactory = $request_factory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory  = $stream_factory ?? Psr17FactoryDiscovery::findStreamFactory();
    }

    public function getHttpClient(): HttpMethodsClientInterface
    {
        if ($this->clientModified) {
            $this->clientModified = false;

            $plugins = $this->plugins;

            if ($this->cache) {
                $plugins[] = $this->cache;
            }

            $this->plugin_client = new HttpMethodsClient(
                ( new PluginClientFactory() )->createClient($this->client, $plugins),
                $this->requestFactory,
                $this->streamFactory,
            );
        }

        return $this->plugin_client;
    }

    public function addPlugin(Plugin $plugin)
    {
        $this->plugins[]       = $plugin;
        $this->clientModified = true;
    }

    public function removePlugin(string $fqcn): void
    {
        foreach ($this->plugins as $idx => $plugin) {
            if (! ( $plugin instanceof $fqcn )) {
                continue;
            }

                unset($this->plugins[ $idx ]);
                $this->clientModified = true;
        }
    }

    public function clearHeaders(): void
    {
        $this->headers = array();

        $this->addHeaders(array());
    }

    public function addHeaders(array $headers): void
    {
        $this->headers = \array_merge($this->headers, $headers);

        $this->removePlugin(Plugin\HeaderAppendPlugin::class);
        $this->addPlugin(new Plugin\HeaderAppendPlugin($this->headers));
    }

    public function addHeaderValue(string $header, string $value): void
    {
        $this->headers[ $header ] = \array_merge(
            (array) ( $this->headers[ $header ] ?? array() ),
            array( $value ),
        );

        $this->addHeaders(array());
    }
}
