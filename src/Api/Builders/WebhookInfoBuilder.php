<?php

namespace Olsgreen\AdobeSign\Api\Builders;

use Olsgreen\AdobeSign\Api\Enums\ResourceTypes;

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

    public function webhookUrlInfo()
    {
        return $this->webhookUrlInfo;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setUrl(string $url)
    {
        $this->webhookUrlInfo()->setUrl($url);

        return $this;
    }

    public function getUrl()
    {
        return $this->webhookUrlInfo()->getUrl();
    }

    public function setScope(string $scope)
    {
        $scopes = new Enums\WebhookScopes();

        if (!$scopes->contains($scope)) {
            throw new \Exception(
                sprintf('Invalid scope provided. [%s]', $scope)
            );
        }

        $this->scope = $scope;

        return $this;
    }

    public function getScope()
    {
        return $this->scope;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setResourceId(string $id)
    {
        $this->resourceId = $id;

        return $this;
    }

    public function setResourceType(string $type)
    {
        $types = new Enums\ResourceTypes();

        if (!$types->contains($type)) {
            throw new \Exception(
                sprintf('Invalid resourceType provided. [%s]', $type)
            );
        }

        $this->resourceType = $type;
    }

    public function getResourceType()
    {
        return $this->resourceType;
    }

    public function setWebhookSubscriptionEvents(array $events)
    {
        $eventNames = new Enums\WebhookEventNames();

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

    public function getWebhookSubscriptionEvents()
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