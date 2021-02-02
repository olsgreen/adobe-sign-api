<?php

namespace Olsgreen\AdobeSign\Api\Builders;

class WebhookUrlInfoBuilder extends AbstractBuilder implements BuilderInterface
{
    protected $url;

    public function setUrl(string $url)
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function validate()
    {
        $errors = [];

        if (is_null($this->url)) {
            $errors['url'] = 'The url must be set.';
        } elseif (!filter_var($this->url, FILTER_VALIDATE_URL)) {
            $errors['url'] = 'The URL is not valid.';
        }

        return empty($errors) ? true : $errors;
    }

    public function make(): array
    {
        $this->validateOrThrow();
        
        return $this->filterMakeOutput([
            'url' => $this->url
        ]);
    }
}