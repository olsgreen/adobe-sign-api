<?php

namespace Olsgreen\AdobeSign\Api\Builders;

class FileInfoBuilder extends AbstractBuilder implements BuilderInterface
{
    protected $transientDocumentId;

    protected $label;

    protected $libraryDocumentId;

    public function setTransientDocumentId(string $id)
    {
        $this->transientDocumentId = $id;

        return $this;
    }

    public function getTransientDocumentId()
    {
        return $this->transientDocumentId;
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

    public function setLibraryDocumentId(string $id)
    {
        $this->libraryDocumentId = $id;

        return $this;
    }

    public function getLibraryDocumentId()
    {
        return $this->libraryDocumentId;
    }

    public function validate()
    {
        return true;
    }

    public function  make(): array
    {
        $this->validateOrThrow();
        
        return array_filter([
            'label' => $this->label,
            'transientDocumentId' => $this->transientDocumentId,
            'libraryDocumentId' => $this->libraryDocumentId,
        ]);
    }
}