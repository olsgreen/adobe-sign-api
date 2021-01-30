<?php

namespace Olsgreen\AdobeSign\Api\Enums;

class WebhookScopes extends AbstractEnum implements EnumInterface
{
    const ACCOUNT = 'ACCOUNT',
          GROUP = 'GROUP',
          USER = 'USER',
          RESOURCE = 'RESOURCE';
}