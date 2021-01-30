<?php

namespace Olsgreen\AdobeSign\Api\Builders;

class Factory
{
    public static function createAgreementInfoBuilder(): AgreementInfoBuilder
    {
        return new AgreementInfoBuilder();
    }

    public static function createFileInfoBuilder(): FileInfoBuilder
    {
        return new FileInfoBuilder();
    }

    public static function createParticipantInfoBuilder(): ParticipantSetInfoBuilder
    {
        return new ParticipantSetInfoBuilder();
    }

    public static function createParticipantSetInfoBuilder(): ParticipantSetInfoBuilder
    {
        return new ParticipantSetInfoBuilder();
    }

    public static function createPhoneInfoBuilder(): PhoneInfoBuilder
    {
        return new PhoneInfoBuilder();
    }

    public static function createWebhookInfoBuilder(): WebhookInfoBuilder
    {
        return new WebhookInfoBuilder();
    }

    public static function createWebhookUrlInfoBuilder(): WebhookUrlInfoBuilder
    {
        return new WebhookUrlInfoBuilder();
    }
}