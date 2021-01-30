<?php

namespace Olsgreen\AdobeSign;

use Olsgreen\AdobeSign\Http\ClientInterface;
use Olsgreen\AdobeSign\Http\GuzzleClient;

class Client
{
    const AUTH_TYPE_BEARER = 'bearer';

    protected $http;

    protected $dataCenter = 'eu2';

    protected $version = 'v6';

    public function __construct(array $options = [], ClientInterface $http = null)
    {
        $this->http = $http ?? new GuzzleClient();

        $this->configureFromArray($options);
    }

    public function getHttp()
    {
        return $this->http;
    }

    protected function configureFromArray(array $options)
    {
        if (isset($options['dataCenter'])) {
            $this->dataCenter = $options['dataCenter'];
        }

        if (isset($options['version'])) {
            $this->version = $options['version'];
        }

        $baseUri = 'https://api.' . $this->dataCenter . '.adobesign.com/api/rest/' . $this->version;

        if (isset($options['baseUri'])) {
            $baseUri = $options['baseUri'];
        }

        $this->http->setBaseUri($baseUri);
    }

    public function authenticate(string $token, string $type = self::AUTH_TYPE_BEARER): Client
    {
        if ($type === static::AUTH_TYPE_BEARER) {
            $this->http->withHeader('Authorization', 'Bearer ' . $token);

            return $this;
        }

        throw new \Exception('Invalid authentication type.');
    }

    public function agreements()
    {
        return new Api\Agreements($this);
    }

    public function documents()
    {
        return new Api\Documents($this);
    }

    public function webhooks()
    {
        return new Api\Webhooks($this);
    }
}