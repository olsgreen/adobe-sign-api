<?php

namespace Olsgreen\AdobeSign\Api\Builders;

class ParticipantInfoBuilder extends AbstractBuilder implements BuilderInterface
{
    protected $email;

    protected $securityOption;

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setSecurityOption(PhoneInfoBuilder $builder)
    {
        $this->securityOption = $builder;

        return $this;
    }

    public function getSecurityOption()
    {
        return $this->securityOption;
    }

    public function validate()
    {
        $errors = [];

        if (is_null($this->email)) {
            $errors['email'] = 'The email must be set.';
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'The email specified is invalid';
        }

        return empty($errors) ? true : $errors;
    }

    public function make(): array
    {
        $this->validateOrThrow();
        
        return array_filter([
            'email' => $this->email,
            'securityOption' => $this->securityOption
        ]);
    }
}