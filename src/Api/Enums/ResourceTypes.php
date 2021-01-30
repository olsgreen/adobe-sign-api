<?php

namespace Olsgreen\AdobeSign\Api\Enums;

class ResourceTypes extends AbstractEnum implements EnumInterface
{
    const AGREEMENT = 'AGREEMENT',
          WIDGET = 'WIDGET',
          MEGASIGN = 'MEGASIGN',
          LIBRARY_DOCUMENT = 'DOCUMENT';
}