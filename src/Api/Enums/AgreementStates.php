<?php

namespace Olsgreen\AdobeSign\Api\Enums;

class AgreementStates extends AbstractEnum implements EnumInterface
{
    const AUTHORING = 'AUTHORING', 
          DRAFT = 'DRAFT', 
          IN_PROCESS = 'IN_PROCESS';
}