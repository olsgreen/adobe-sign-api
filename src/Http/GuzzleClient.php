<?php

namespace Olsgreen\AdobeSign\Http;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

class GuzzleClient implements ClientInterface
{
    protected $guzzle;

    protected $baseUri = '';

    protected $headers = [];

    public function __construct(GuzzleClientInterface $guzzle = null)
    {
        if (!$guzzle) {
            $guzzle = new Client();
        }

        $this->guzzle = $guzzle;
    }

    public function setBaseUri(string $uri)
    {
        $this->baseUri = $uri;

        return $this;
    }

    public function withHeader(string $key, string $value)
    {
        $this->headers[$key] = $value;

        return $this;
    }

    protected function makeRequestObject(
        string $method, 
        string $uri, 
        array $params = [], 
        $body = null, 
        array $headers = []
    )
    {
        $headers = array_merge($this->headers, $headers);

        $uri = $this->baseUri . $uri . '?' . http_build_query($params);

        return new Request($method, $uri, $headers, $body);
    }

    public function get(string $uri, array $params = [], array $headers = []): ResponseInterface
    {
        $request = $this->makeRequestObject('GET', $uri, $params, null, $headers);

        return $this->guzzle->send($request);
    }

    public function post(string $uri, array $params = [], $body = null, array $headers = []): ResponseInterface
    {
        if ($body instanceof SimpleMultipartBody) {
            $body = new MultipartStream($body->toArray());
        }

        $request = $this->makeRequestObject('POST', $uri, $params, $body, $headers);

        return $this->guzzle->send($request);
    }

    public function put(string $uri, array $params = [], $body = null, array $headers = []): ResponseInterface
    {

    }

    public function delete(string $uri, array $params = [], array $headers = []): ResponseInterface
    {
        $request = $this->makeRequestObject('DELETE', $uri, $params, null, $headers);

        return $this->guzzle->send($request);
    }
}