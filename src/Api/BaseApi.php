<?php //phpcs:disable WordPress.WP.AlternativeFunctions


namespace SureCart\Api;

use Psr\Http\Message\ResponseInterface;
use SureCart\Client;
use SureCart\Message\ResponseMediator;

abstract class BaseApi
{
    private ?int $per_page = null;

    public function __construct(
        private Client $client,
    ) {
    }

    protected function get(string $path, array $params = array(), array $headers = array())
    {
        if (null !== $this->per_page && ! isset($params['limit']) && ! isset($params['per_page'])) {
            $params['limit'] = $this->per_page;
        }

        if (\count($params) > 0) {
            $path .= $this->buildQuery($params);
        }

        $response = $this->client->getHttpClient()->get($path, $headers);

        return ResponseMediator::getContent($response);
    }

    protected function head(string $path, array $params = array(), array $headers = array()): ResponseInterface
    {
        return $this->client->getHttpClient()->head(
            $path . '?' . $this->buildQuery($params),
            $headers,
        );
    }

    protected function post(string $path, array $params = array(), array $headers = array())
    {
        return $this->postRaw(
            $path,
            $this->createJsonBody($params),
            $headers,
        );
    }

    protected function patch(string $path, array $params = array(), array $headers = array())
    {
        return $this->postRaw(
            $path,
            $this->createJsonBody($params),
            $headers,
            'patch',
        );
    }

    protected function postRaw(string $path, string $body, array $headers = array(), string $method = 'post')
    {
        $response = $this->client->getHttpClient()->$method($path, $headers, $body);

        return ResponseMediator::getContent($response);
    }

    protected function deleteRaw(string $path, array $params = array(), array $headers = array())
    {
        if (\count($params) > 0) {
            $path .= $this->buildQuery($params);
        }

        $response =  $this->client->getHttpClient()->delete($path, $headers);

        return ResponseMediator::getContent($response);
    }

    /**
     * Create a JSON encoded version of an array of parameters.
     *
     * @param array $params Request parameters
     *
     * @return string|null
     */
    protected function createJsonBody(array $params): ?string
    {
        if (0 === \count($params)) {
            return null;
        }

        return \json_encode($params, $this->getJsonEncodeArgs());
    }

    protected function getJsonEncodeArgs(): int
    {
        return \JSON_FORCE_OBJECT;
    }

    protected function buildQuery(array $params): string
    {
        return '?' . \http_build_query($params, '', '&', PHP_QUERY_RFC3986);
    }

    protected function buildPath(string ...$replacements): string
    {
        return \rtrim(
            \vsprintf(
                '/' . $this->client->getApiPrefix() . $this->getPath(),
                \array_map('rawurlencode', $replacements),
            ),
            '/',
        );
    }

    abstract protected function getPath(): string;

    protected function getClient(): Client
    {
        return $this->client;
    }
}
