 # Adobe Sign PHP API Client
[![Latest Version](https://img.shields.io/github/release/olsgreen/adobe-sign-api.svg?style=flat-square)](https://github.com/olsgreen/adobe-sign-api/releases)
[![Tests](https://github.com/olsgreen/adobe-sign-api/workflows/Tests/badge.svg)](https://github.com/olsgreen/adobe-sign-api/actions)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

This package provides a means easily of interacting with the Adobe Sign API v6.

## Installation

Add the client to your project using composer.

    composer require olsgreen/adobe-sign-api

## Usage

The client assumes you have and access token from the Adobe Sign oAuth flow, I have written a separate package to deal with this, see [olsgreen/oauth2-adobe-sign](https://github.com/olsgreen/oauth2-adobe-sign).

### Create a new API client instance & authenticate

```php
// Create an instance of the client.
$api = new \Olsgreen\AdobeSign\Client($your_access_token, ['data_center' => 'us2']);
```

### Uploading a file

```php
// Upload a document from a path
$transientDocumentId = $api->documents()->uploadFile('/path/to/my/file.pdf');

// Upload a stream, resource or binary data.
$data = fopen('/path/to/my/file.pdf', 'r');
$transientDocumentId = $api->documents()->upload('file.pdf', 'application/pdf', $data);
```

### Building, creating and sending an Agreement using builders

```php
use \Olsgreen\AdobeSign\Api\Builders\Factory;
use \Olsgreen\AdobeSign\Api\Enums\SignatureTypes;
use \Olsgreen\AdobeSign\Api\Enums\AgreementStates;
use \Olsgreen\AdobeSign\Api\Enums\ParticipantRoles;
...

// Create the base agreement object.
$agreement = Factory::newAgreementInfoBuilder()
    ->setName('Test Agreement')
    ->setSignatureType(SignatureTypes::TYPE_ESIGN)
    ->setState(AgreementStates::IN_PROCESS);

// Add the PDF file for signing to the agreement using the 
// $transientDocumentId from the document upload example above.
$agreement->fileInfos()->add(
    Factory::newFileInfoBuilder()
        ->setLabel('Test File')
        ->setTransientDocumentId($transientDocumentId)
);

// Create a participent set for the signer.
$participantSetsInfo = Factory::newParticipantSetInfoBuilder()
    ->setOrder(1)
    ->setRole(ParticipantRoles::SIGNER);

// Add a participent to the set.
$participantSetsInfo->memberInfos()->add(
    Factory::newParticipantInfoBuilder()
        ->setEmail('signer@domain.com')
);

// Add the participent set to the agreement.
$agreement->participantSetsInfo()->add($participantSetsInfo);

// Create the agreement via the API.
$agreementId = $api->agreements()->create($agreement);
```


###  Get an Agreements Signing URLs

```php
// Gets the signing URLs from the API.
$urls = $api->agreements()->getSigningUrls($agreementId);

// Will output similar to:
// [
//     ['email' => 'signer1@domain.com', 'url' => 'https://secure.adobesign.com/sign1'],
//     ['email' => 'signer2@domain.com', 'url' => 'https://secure.adobesign.com/sign2']
// ]
```

### Create a Webhook

```php
use \Olsgreen\AdobeSign\Api\Builders\WebhookInfoBuilder;
use \Olsgreen\AdobeSign\Api\Enums\WebhookScopes;
use \Olsgreen\AdobeSign\Api\Enums\WebhookEventNames;

...

$webhook = WebhookInfoBuilder::create()
    ->setName('Test Webhook')
    ->setScope(WebhookScopes::USER)
    ->setUrl('https://mydomain.com/hooks/adobe-sign')
    ->setWebhookSubscriptionEvents([
        WebhookEventNames::AGREEMENT_ALL
    ]);

$webhookId = $api->webhooks()->create($webhook);
```