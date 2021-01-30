<?php

namespace Olsgreen\AdobeSign\Api\Builders;

class AgreementInfoBuilder extends AbstractBuilder implements BuilderInterface
{
    protected $fileInfos;

    protected $participantSetsInfo;

    protected $name;

    protected $signatureType;

    protected $state;

    public function __construct()
    {
        $this->fileInfos = new InfoCollection(
            'AgreementInfoBuilder->fileInfos', FileInfoBuilder::class, $minRequiredElements = 1
        );

        $this->participantSetsInfo = new InfoCollection(
            'AgreementInfoBuilder->participantSetsInfo', ParticipantSetInfoBuilder::class, $minRequiredElements = 1
        );
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

    public function setSignatureType($type)
    {
        $types = new Enums\SignatureTypes();

        if (!$types->contains($type)) {
            throw new \Exception(
                sprintf('\'%s\' is an invalid signature type.', $type)
            );
        }

        $this->signatureType = $type;

        return $this;
    }

    public function getSignatureType()
    {
        return $this->signatureType;
    }

    public function setState($state)
    {
        $states = new Enums\AgreementStates();

        if (!$states->contains($state)) {
            throw new \Exception(
                sprintf('\'%s\' is an invalid state.', $state)
            );
        }

        $this->state = $state;

        return $this;
    }

    public function fileInfos(): InfoCollection
    {
        return $this->fileInfos;
    }

    public function participantSetsInfo(): InfoCollection
    {
        return $this->participantSetsInfo;
    }

    public function validate()
    {
        $errors = [];

        if (empty($this->name)) {
            $errors['name'] = 'A name must be set.';
        }

        if (empty($this->state)) {
            $errors['state'] = 'A state must be set.';
        }

        if (empty($this->signatureType)) {
            $errors['signatureType'] = 'A signature type must be set.';
        }

        return empty($errors) ? true : $errors;
    }

    public function make(): array
    {
        $this->validateOrThrow();

        return array_filter([
            'name' => $this->name,
            'signatureType' => $this->signatureType,
            'state' => $this->state,
            'fileInfos' => $this->fileInfos->make(),
            'participantSetsInfo' => $this->participantSetsInfo->make(),
        ]);
    }
}