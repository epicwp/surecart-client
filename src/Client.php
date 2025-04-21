<?php

namespace SureCart;

use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin as PsrPlugin;
use Http\Discovery\Psr17FactoryDiscovery as PsrFinder;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use SureCart\Api\BaseApi;

/**
 * SureCart Client
 *
 * @method Api\Activation activation() Activation API endpoints.
 * @method Api\License    license()    License API endpoints.
 * @method Api\Pub        public()     Public  API endpoints.
 */
class Client
{
    /**
     * Base URL for the API
     *
     * @var string
     */
    private const BASE_URL = 'https://api.surecart.com';

    /**
     * Default headers
     *
     * @var array
     */
    private const HEADERS = array(
        'Accept'       => 'application/json',
        'Content-Type' => 'application/json',
    );

    private string $prefix;

    private Builder $builder;

    /**
     *
     *
     * @var Plugin\History
     */
    private PsrPlugin\Journal $history;

    public function __construct(
        Builder $builder = null,
        string $apiKey = null,
        string $baseUrl = null,
        string $prefix = null,
    ) {
        $this->history = new Plugin\History();
        $this->builder = $builder ?? new Builder();
        $this->prefix = $prefix ?? 'v1';

        $this->builder->addPlugin(new PsrPlugin\HistoryPlugin($this->history));
        $this->builder->addPlugin(new PsrPlugin\RedirectPlugin());
        $this->builder->addPlugin(new PsrPlugin\AddHostPlugin($this->getBaseUrl($baseUrl)));
        $this->builder->addPlugin(new PsrPlugin\HeaderDefaultsPlugin(Client::HEADERS));
        $this->builder->addPlugin(new Plugin\Authentication($apiKey ?? ''));
    }

    public static function withHttpClient(ClientInterface $client): static
    {
        // @phpstan-ignore new.static
        return new static((new Builder($client)));
    }

    protected function getBaseUrl(?string $baseUrl): \Psr\Http\Message\UriInterface
    {
        return PsrFinder::findUriFactory()->createUri($baseUrl ?? self::BASE_URL);
    }

    /**
     * Call an API endpoint
     *
     * @param  string       $name The name of the API endpoint
     * @return BaseApi|null       The API endpoint
     */
    protected function api(string $name): ?BaseApi
    {
        return match ($name) {
            'activation'  => new Api\Activation($this),
            'license'     => new Api\License($this),
            'public'      => new Api\Pub($this),
            default       => throw new \Exception('tbd'),
        };
    }

    public function __call(string $name, array $args): BaseApi
    {
        try {
            return $this->api($name);
        } catch (\Exception $e) {
            throw new \BadMethodCallException($e->getMessage());
        }
    }

    public function auth(string $apiKey): static
    {
        $this->getBuilder()->removePlugin(Plugin\Authentication::class);
        $this->getBuilder()->addPlugin(new Plugin\Authentication($apiKey));

        return $this;
    }

    protected function getBuilder(): Builder
    {
        return $this->builder;
    }

    public function getHttpClient(): HttpMethodsClientInterface
    {
        return $this->getBuilder()->getHttpClient();
    }

    public function getApiPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * Get the last response
     *
     * @return null|ResponseInterface
     */
    public function getLastResponse(): ?ResponseInterface
    {
        return $this->history->getLastResponse();
    }
}
