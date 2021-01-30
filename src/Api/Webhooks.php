<?php

namespace Olsgreen\AdobeSign\Api;

use Olsgreen\AdobeSign\Api\Enums\ResourceTypes;
use Olsgreen\AdobeSign\Api\Enums\WebhookScopes;
use Olsgreen\AdobeSign\Api\Builders\WebhookInfoBuilder;

class Webhooks extends AbstractApi
{
    public function create(WebhookInfoBuilder $builder, bool $raw = false)
    {
        $body = json_encode($builder->make(), JSON_PRETTY_PRINT);

        $headers = [
            'Content-Type' => 'application/json'
        ];
        
        $response = $this->_post('/webhooks', [], $body, $headers);

        return $raw ? $response : $response['id'];
    }

    public function all(array $parameters = [])
    {
        $resolver = $this->createOptionsResolver();

        $resolver->setDefined('resourceType')
            ->setAllowedValues('resourceType', function ($value): bool {
                return (new ResourceTypes())->contains($value);
            });

        $resolver->setDefined('scope')
            ->setAllowedValues('scope', function ($value): bool {
                return (new WebhookScopes())->contains($value);
            });

        $resolver->setDefined('showInActiveWebhooks')
            ->setAllowedTypes('showInActiveWebhooks', 'boolean');

        return $this->_get('/webhooks', $resolver->resolve($parameters));
    }

    public function delete(string $id, bool $raw = false)
    {
        $response = $this->_delete('/webhooks/' . urlencode($id));

        return $raw ? $response : $response === [];
    }
}