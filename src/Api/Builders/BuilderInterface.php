<?php

namespace Olsgreen\AdobeSign\Api\Builders;

interface BuilderInterface
{
    public function validate();
    public function make(): array;
}