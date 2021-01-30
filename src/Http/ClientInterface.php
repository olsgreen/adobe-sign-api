<?php

namespace Olsgreen\AdobeSign\Http;

use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{
    public function setBaseUri(string $uri);
    public function withHeader(string $key, string $value);
    public function get(string $uri, array $params = [], array $headers = []): ResponseInterface;
    public function post(string $uri, array $params = [], array $headers = [], array $files = []): ResponseInterface;
    public function put(string $uri, array $params = [], array $headers = [], array $files = []): ResponseInterface;
    public function delete(string $uri, array $params = [], array $headers = []): ResponseInterface;
}