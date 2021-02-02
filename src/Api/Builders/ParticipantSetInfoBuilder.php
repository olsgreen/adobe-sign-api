<?php

namespace Olsgreen\AdobeSign\Api\Builders;

use Olsgreen\AdobeSign\Api\Enums\ParticipantRoles;

class ParticipantSetInfoBuilder extends AbstractBuilder implements BuilderInterface
{
    protected $memberInfos;

    protected $order = 1;

    protected $role;

    protected $label;

    protected $name;

    protected $privateMessage;

    protected $visiblePages = [];

    public function __construct()
    {
        $this->memberInfos = new InfoCollection(
            'ParticipantInfoBuilder->memberInfos', 
            ParticipantInfoBuilder::class,
            1
        );
    }

    public function memberInfos()
    {
        return $this->memberInfos;
    }

    public function setOrder($order)
    {
        if (intval($order) < 1) {
            throw new \Exception('The order must be 1 or greater.');
        }

        $this->order = intval($order);

        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setRole(string $role)
    {
        $roles = new ParticipantRoles();

        if (!$roles->contains($role)) {
            throw new \Exception(
                sprintf('\'%s\' is not a valid role.', $role)
            );
        }

        $this->role = $role;

        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setLabel(string $label)
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel()
    {
        return $this->label;
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

    public function setPrivateMessage(string $message)
    {
        $this->privateMessage = $message;

        return $this;
    }

    public function getPrivateMessage()
    {
        return $this->privateMessage;
    }

    public function setVisiblePages(array $labels)
    {
        $this->visiblePages = $labels;

        return $this;
    }

    public function getVisiblePages()
    {
        return $this->visiblePages;
    }

    public function validate()
    {
        $errors = [];

        if (is_null($this->order)) {
            $errors['order'] = 'The order must be set.';
        }

        if (empty($this->role)) {
            $errors['role'] = 'The role must be set.';
        }

        return empty($errors) ? true : $errors;
    }

    public function make(): array
    {
        $this->validateOrThrow();
        
        return $this->filterMakeOutput([
            'order' => $this->order,
            'role' => $this->role,
            'label' => $this->label,
            'name' => $this->name,
            'privateMessage' => $this->privateMessage,
            'visiblePages' => $this->visiblePages,
            'memberInfos' => $this->memberInfos->make(),
        ]);
    }
}