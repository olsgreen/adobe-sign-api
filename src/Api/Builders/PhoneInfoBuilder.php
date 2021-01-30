<?php

namespace Olsgreen\AdobeSign\Api\Builders;

class PhoneInfoBuilder extends AbstractBuilder implements BuilderInterface
{
    protected $countryCode;

    protected $countryIsoCode;

    protected $phone;

    public function setCountryCode($code)
    {
        $this->countryCode = $code;

        return $this;
    }

    public function getCountryCode()
    {
        return $this->countryCode;
    }

    public function setCountryIsoCode($isoCode)
    {
        $this->countryIsoCode = $isoCode;

        return $this;
    }

    public function getCountryIsoCode()
    {
        return $this->countryIsoCode;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function validate()
    {
        $errors = [];

        if (is_null($this->phone)) {
            $errors['phone'] = 'The phone must be set.';
        }

        if (is_null($this->countryCode)) {
            $errors['countryCode'] = 'The country code must be set.';
        }

        if (is_null($this->countryIsoCode)) {
            $errors['countryIsoCode'] = 'The ISO country code must be set.';
        }

        return empty($errors) ? true : $errors;
    }

    public function make(): array
    {
        $this->validateOrThrow();
        
        return [
            'phone' => $this->phone,
            'countryCode' => $this->countryCode,
            'countryIsoCode' => $this->countryIsoCode,
        ];
    }
}