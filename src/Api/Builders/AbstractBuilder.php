<?php

namespace Olsgreen\AdobeSign\Api\Builders;

abstract class AbstractBuilder
{
    public static function create()
    {
        return new static();
    }

    protected function filterMakeOutput(array $output)
    {
        return array_filter($output, function($item) {
            return !is_null($item);
        });
    }

    protected function validateOrThrow()
    {
        $errors = $this->validate();

        if ($errors !== true) {
            $keys = array_keys($errors);
            $invalidPropsString = implode(',' , $keys);

            throw new \Exception(
                sprintf(
                    '%s: Some properties have invalid values. [%s]', 
                    static::class,
                    $invalidPropsString
                )
            );
        }
    }
}