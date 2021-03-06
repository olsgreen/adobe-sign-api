<?php


namespace Olsgreen\AdobeSign\Http;

use Closure;
use Psr\Http\Message\RequestInterface;

/**
 * AbstractClient
 * Base client that implements non-driver specific
 * functionality to provide HTTP communication.
 */
abstract class AbstractClient implements ClientInterface
{
    /**
     * The base URI to perform all requests.
     *
     * @var string
     */
    protected $baseUri = '';

    /**
     * Global headers for all requests.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * A closure to execute before every request.
     *
     * @var Closure
     */
    protected $preflightCallback;

    /**
     * Access token to set for each request.
     *
     * @var mixed
     */
    protected $accessToken;

    /**
     * Set the clients base URI.
     *
     * @param string $uri
     * @return ClientInterface
     */
    public function setBaseUri(string $uri): ClientInterface
    {
        $this->baseUri = $uri;

        return $this;
    }

    /**
     * Get the clients base URI.
     *
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    /**
     * Add a global header.
     *
     * @param string $key
     * @param string $value
     * @return ClientInterface
     */
    public function withHeader(string $key, string $value): ClientInterface
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Get the current global headers.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Set the access token.
     *
     * @param $token
     * @return ClientInterface
     */
    public function setAccessToken($token): ClientInterface
    {
        $this->accessToken = $token;

        $this->withHeader('Authorization', 'Bearer ' . $token);

        return $this;
    }

    /**
     * Get the access token.
     *
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set the callback to be performed before every request.
     *
     * @param Closure $callback
     * @return ClientInterface
     */
    public function setPreflightCallback(Closure $callback): ClientInterface
    {
        $this->preflightCallback = $callback;

        return $this;
    }

    /**
     * Execute the preflight callback.
     *
     * @param RequestInterface $request
     * @return ClientInterface
     */
    public function doPreflightCallback(RequestInterface &$request): ClientInterface
    {
        if ($this->preflightCallback) {
            call_user_func_array(
                $this->preflightCallback,
                [$request, $this]
            );
        }

        return $this;
    }
}