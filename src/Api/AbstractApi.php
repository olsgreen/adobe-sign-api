<?php

namespace Olsgreen\AdobeSign\Api;

use Olsgreen\AdobeSign\Client;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractApi
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    protected function isEmptyResponse(ResponseInterface $response): bool
    {
        return !$response->hasHeader('Content-Type') && 
            empty((string) $response->getBody());
    }

    protected function isJsonResponse(ResponseInterface $response): bool
    {
        if ($response->hasHeader('Content-Type')) {
            $types = array_map(function($type) {
                $clean = explode(';', $type);
                return $clean[0];
            }, $response->getHeader('Content-Type'));

            return in_array('application/json', $types);
        }

        return false;
    }

    protected function parseResponse(ResponseInterface $response): array
    {
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() <= 299) {
            if ($this->isJsonResponse($response)) {
                $decoded = json_decode((string) $response->getBody(), true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception(
                        sprintf(
                            'There was a problem decoding the response body: %s', 
                            json_last_error_msg()
                        )
                    );
                }

                return $decoded;
            } elseif ($this->isEmptyResponse($response)) {
                return [];
            }

            throw new \Exception('The response had an unsupported Content-Type.');
        }

        /*if (isset($res['code'])) {
            if ($res['code'] == 'INVALID_ACCESS_TOKEN') {
                throw new AdobeSignInvalidAccessTokenException($res['code'] . ': ' . $res['message']);
            } elseif ($res['code'] == 'UNSUPPORTED_MEDIA_TYPE') {
                throw new AdobeSignUnsupportedMediaTypeException($res['code'] . ': ' . $res['message']);
            } elseif ($res['code'] == 'MISSING_REQUIRED_PARAM') {
                throw new AdobeSignMissingRequiredParamException($res['code'] . ': ' . $res['message']);
            } else {
                throw new AdobeSignException($res['code'] . ': ' . $res['message']);
            }
        }*/
    }

    protected function _get(string $uri, array $params = [], array $headers = []): array
    {
        $response = $this->client->getHttp()->get($uri, $params, $headers);

        return $this->parseResponse($response);
    }

    protected function _post(string $uri, array $params = [], $body = null, array $headers = []): array
    {
        $response = $this->client->getHttp()->post($uri, $params, $body, $headers);

        return $this->parseResponse($response);
    }

    protected function _delete(string $uri, array $params = [], array $headers = []): array
    {
        $response = $this->client->getHttp()->delete($uri, $params, $headers);

        return $this->parseResponse($response);
    }
    
    protected function createOptionsResolver(): OptionsResolver
    {
        $resolver = new OptionsResolver();
        $resolver->setDefined('cursor')
            ->setAllowedTypes('cursor', 'int')
            ->setAllowedValues('cursor', function ($value): bool {
                return $value > 0;
            });

        $resolver->setDefined('per_page')
            ->setAllowedTypes('per_page', 'int')
            ->setAllowedValues('per_page', function ($value): bool {
                return $value > 0 && $value <= 100;
            });

        return $resolver;
    }  
}