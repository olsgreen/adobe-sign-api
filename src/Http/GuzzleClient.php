<?php

namespace Olsgreen\AdobeSign\Http;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * GuzzleClient
 * An client implementation that wraps GuzzleHTTP to provide HTTP communication.
 */
class GuzzleClient extends AbstractClient implements ClientInterface
{
    /**
     * Guzzle HTTP Client Instance.
     *
     * @var Client|GuzzleClientInterface|null
     */
    protected $guzzle;

    /**
     * GuzzleClient constructor.
     * @param GuzzleClientInterface|null $guzzle
     */
    public function __construct(GuzzleClientInterface $guzzle = null)
    {
        if (!$guzzle) {
            $guzzle = new Client();
        }

        $this->guzzle = $guzzle;
    }

    /**
     * Create a request object instance.
     *
     * @param string $method
     * @param string $uri
     * @param array $params
     * @param null $body
     * @param array $headers
     * @return Request
     */
    protected function createRequestObject(
        string $method, 
        string $uri, 
        array $params = [], 
        $body = null, 
        array $headers = []
    ): RequestInterface
    {
        $headers = array_merge($this->headers, $headers);

        $uri = $this->baseUri . $uri . '?' . http_build_query($params);

        $request = new Request($method, $uri, $headers, $body);

        $this->doPreflightCallback($request);

        return $request;
    }

    /**
     * Execute a GET request.
     *
     * @param string $uri
     * @param array $params
     * @param array $headers
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(string $uri, array $params = [], array $headers = []): ResponseInterface
    {
        $request = $this->createRequestObject('GET', $uri, $params, null, $headers);

        return $this->guzzle->send($request);
    }

    /**
     * Execute a POST request.
     *
     * @param string $uri
     * @param array $params
     * @param null $body
     * @param array $headers
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post(string $uri, array $params = [], $body = null, array $headers = []): ResponseInterface
    {
        if ($body instanceof SimpleMultipartBody) {
            $body = new MultipartStream($body->toArray());
        }

        $request = $this->createRequestObject('POST', $uri, $params, $body, $headers);

        return $this->guzzle->send($request);
    }

    /**
     * Execute a PUT request.
     *
     * @param string $uri
     * @param array $params
     * @param null $body
     * @param array $headers
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function put(string $uri, array $params = [], $body = null, array $headers = []): ResponseInterface
    {

    }

    /**
     * Execute a DELETE request.
     *
     * @param string $uri
     * @param array $params
     * @param array $headers
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(string $uri, array $params = [], array $headers = []): ResponseInterface
    {
        $request = $this->createRequestObject('DELETE', $uri, $params, null, $headers);

        return $this->guzzle->send($request);
    }
}