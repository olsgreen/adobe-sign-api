<?php

namespace Olsgreen\AdobeSign;

use Olsgreen\AdobeSign\Http\ClientInterface;
use Olsgreen\AdobeSign\Http\GuzzleClient;
use Closure;

/**
 * Client
 * Principal project class, provides the core
 * functionality of the library.
 */
class Client
{
    /**
     * HTTP Client Instance
     *
     * @var ClientInterface|GuzzleClient
     */
    protected $http;

    /**
     * Adobe Sign Data Center Code
     *
     * @var string
     */
    protected $dataCenter = 'eu2';

    /**
     * Adobe Sign API Version
     *
     * @var string
     */
    protected $version = 'v6';

    /**
     * Client constructor.
     *
     * @param $accessToken
     * @param array $options
     * @param ClientInterface|null $http
     */
    public function __construct($accessToken, array $options = [], ClientInterface $http = null)
    {
        $this->http = $http ?? new GuzzleClient();

        $this->setAccessToken($accessToken);

        $this->configureFromArray($options);
    }

    /**
     * Get the underlying HTTP client instance.
     *
     * @return ClientInterface|GuzzleClient
     */
    public function getHttp()
    {
        return $this->http;
    }

    /**
     * Set this clients options from array.
     *
     * @param array $options
     */
    protected function configureFromArray(array $options)
    {
        if (isset($options['data_center'])) {
            $this->dataCenter = $options['data_center'];
        }

        if (isset($options['version'])) {
            $this->version = $options['version'];
        }

        $baseUri = 'https://api.' . $this->dataCenter .
            '.adobesign.com/api/rest/' . $this->version;

        $this->http->setBaseUri($baseUri);
    }

    /**
     * Register a callback to be executed before each request.
     *
     * @param Closure $callback
     * @return $this
     */
    public function preflight(Closure $callback): self
    {
        $this->http->setPreflightCallback($callback);

        return $this;
    }

    /**
     * Set the access token.
     *
     * @param $token
     * @return $this
     */
    public function setAccessToken($token): Client
    {
        $this->http->setAccessToken($token);

        return $this;
    }

    /**
     * Get the access token.
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->http->getAccessToken();
    }

    /**
     * Agreements.
     *
     * @return Api\Agreements
     */
    public function agreements()
    {
        return new Api\Agreements($this);
    }

    /**
     * Documents.
     *
     * @return Api\Documents
     */
    public function documents()
    {
        return new Api\Documents($this);
    }

    /**
     * Webhooks.
     *
     * @return Api\Webhooks
     */
    public function webhooks()
    {
        return new Api\Webhooks($this);
    }
}