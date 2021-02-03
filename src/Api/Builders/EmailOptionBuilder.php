<?php


namespace Olsgreen\AdobeSign\Api\Builders;


class EmailOptionBuilder extends AbstractBuilder implements BuilderInterface
{
    protected $sendOptions;

    public function __construct()
    {
        $this->sendOptions = new SendOptionsBuilder();
    }

    public function sendOptions(): SendOptionsBuilder
    {
        return $this->sendOptions;
    }

    public function validate()
    {
        return true;
    }

    public function make(): array
    {
        return $this->filterMakeOutput([
            'sendOptions' => $this->sendOptions->make(),
        ]);
    }
}