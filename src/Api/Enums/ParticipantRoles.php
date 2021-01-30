<?php

namespace Olsgreen\AdobeSign\Api\Enums;

class ParticipantRoles extends AbstractEnum implements EnumInterface
{
    const SIGNER = 'SIGNER',
          APPROVER = 'APPROVER',
          ACCEPTOR = 'ACCEPTOR',
          CERTIFIED_RECIPIENT = 'CERTIFIED_RECIPIENT',
          FORM_FILLER = 'FORM_FILLER',
          DELEGATE_TO_SIGNER = 'DELEGATE_TO_SIGNER',
          DELEGATE_TO_APPROVER = 'DELEGATE_TO_APPROVER',
          DELEGATE_TO_ACCEPTOR = 'DELEGATE_TO_ACCEPTOR',
          DELEGATE_TO_CERTIFIED_RECIPIENT = 'DELEGATE_TO_CERTIFIED_RECIPIENT',
          DELEGATE_TO_FORM_FILLER = 'DELEGATE_TO_FORM_FILLER',
          SHARE = 'SHARE';
}
