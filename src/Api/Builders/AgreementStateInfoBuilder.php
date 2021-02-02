<?php


namespace Olsgreen\AdobeSign\Api\Builders;


use Olsgreen\AdobeSign\Api\Enums\AgreementStates;

class AgreementStateInfoBuilder extends AbstractBuilder implements BuilderInterface
{
    protected $state;

    protected $agreementCancellationInfo;

    public function __construct()
    {
        $this->agreementCancellationInfo = new AgreementCancellationInfoBuilder();
    }

    public function setState(string $state)
    {
        $states = new AgreementStates();

        if (!$states->contains($state)) {
            throw new \Exception(
                sprintf('\'%s\' is an invalid state.', $state)
            );
        }

        $this->state = $state;

        return $this;
    }

    public function getState()
    {
        return $this->state;
    }

    public function agreementCancellationInfo(): AgreementCancellationInfoBuilder
    {
        return $this->agreementCancellationInfo;
    }

    public function validate()
    {
        $errors = [];

        if (empty($this->state)) {
            $errors['state'] = 'A state must be set.';
        }

        return empty($errors) ? true : $errors;
    }

    public function make(): array
    {
        $this->validateOrThrow();

        return $this->filterMakeOutput([
            'state' => $this->state,
            'agreementCancellationInfo' => $this->agreementCancellationInfo->make(),
        ]);
    }
}