<?php

namespace Olsgreen\AdobeSign\Api\Builders;

use Olsgreen\AdobeSign\Api\Enums\ResourceTypes;
use Olsgreen\AdobeSign\Api\Enums\WebhookEventNames;
use Olsgreen\AdobeSign\Api\Enums\WebhookScopes;

class WebhookInfoBuilder extends AbstractBuilder implements BuilderInterface
{
    const STATE_ACTIVE = 'ACTIVE';

    protected $name;

    protected $scope;

    protected $state = self::STATE_ACTIVE;

    protected $webhookSubscriptionEvents = [];

    protected $webhookUrlInfo;

    protected $resourceId;

    protected $resourceType;

    public function __construct()
    {
        $this->webhookUrlInfo = new WebhookUrlInfoBuilder();
    }

    public function webhookUrlInfo(): WebhookUrlInfoBuilder
    {
        return $this->webhookUrlInfo;
    }

    public function setName(string $name): WebhookInfoBuilder
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setUrl(string $url): WebhookInfoBuilder
    {
        $this->webhookUrlInfo()->setUrl($url);

        return $this;
    }

    public function getUrl(): string
    {
        return $this->webhookUrlInfo()->getUrl();
    }

    public function setScope(string $scope): WebhookInfoBuilder
    {
        $scopes = new WebhookScopes();

        if (!$scopes->contains($scope)) {
            throw new \Exception(
                sprintf('Invalid scope provided. [%s]', $scope)
            );
        }

        $this->scope = $scope;

        return $this;
    }

    public function getScope(): string
    {
        return $this->scope;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setResourceId(string $id): string
    {
        $this->resourceId = $id;

        return $this;
    }

    public function setResourceType(string $type)
    {
        $types = new ResourceTypes();

        if (!$types->contains($type)) {
            throw new \Exception(
                sprintf('Invalid resourceType provided. [%s]', $type)
            );
        }

        $this->resourceType = $type;
    }

    public function getResourceType(): string
    {
        return $this->resourceType;
    }

    public function setWebhookSubscriptionEvents(array $events): WebhookInfoBuilder
    {
        $eventNames = new WebhookEventNames();

        if (!$eventNames->contains($events)) {
            throw new \Exception(
                sprintf(
                    'Invalid events provided. [%s]', 
                    implode(',', $eventNames->diff($events))
                )
            );
        }

        $this->webhookSubscriptionEvents = $events;

        return $this;
    }

    public function getWebhookSubscriptionEvents(): array
    {
        return $this->webhookSubscriptionEvents;
    }

    public function validate()
    {
        $errors = [];

        if (is_null($this->name)) {
            $errors['name'] = 'The name must be set.';
        }

        if (is_null($this->scope)) {
            $errors['scope'] = 'The scope must be set.';
        }

        if (is_null($this->state)) {
            $errors['state'] = 'The state must be set.';
        }

        if (count($this->webhookSubscriptionEvents) === 0) {
            $errors['webhookSubscriptionEvents'] = 'You must set at least one event name.';
        }

        return empty($errors) ? true : $errors;
    }

    public function make(): array
    {
        $this->validateOrThrow();
        
        return array_filter([
            'name' => $this->name,
            'scope' => $this->scope,
            'state' => $this->state,
            'webhookSubscriptionEvents' => $this->webhookSubscriptionEvents,
            'webhookUrlInfo' => $this->webhookUrlInfo->make(),
            'resourceId' => $this->resourceId,
            'resourceType' => $this->resourceType,
        ]);
    }
}