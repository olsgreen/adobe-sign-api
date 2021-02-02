<?php

namespace Olsgreen\AdobeSign\Api;

use Olsgreen\AdobeSign\Api\Builders\AgreementInfoBuilder;
use Olsgreen\AdobeSign\Api\Builders\AgreementStateInfoBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Agreements extends AbstractApi
{
    /**
     * Retrieves agreements for the user.
     *
     * @param array $parameters
     * @return array
     */
    public function all(array $parameters = []): array
    {
        $resolver = $this->createOptionsResolver();

        $resolver->setDefined('externalId')
            ->setAllowedTypes('externalId', 'int')
            ->setAllowedValues('externalId', function ($value): bool {
                return $value > 0;
            });

        $resolver->setDefined('groupId')
            ->setAllowedTypes('groupId', 'int')
            ->setAllowedValues('groupId', function ($value): bool {
                return $value > 0;
            });

        $resolver->setDefined('showHiddenAgreements')
            ->setAllowedTypes('showHiddenAgreements', 'boolean')
            ->setNormalizer('showHiddenAgreements',  $this->booleanNormalizer());

        return $this->_get('/agreements', $resolver->resolve($parameters));
    }

    /**
     * Creates an agreement. Sends it out for signatures, and returns
     * the agreementID in the response to the client.
     *
     * @param AgreementInfoBuilder $builder
     * @param bool $raw
     * @return array|string
     */
    public function create(AgreementInfoBuilder $builder, bool $raw = false)
    {
        $body = json_encode($builder->make(), JSON_PRETTY_PRINT);

        $headers = [
            'Content-Type' => 'application/json'
        ];
        
        $response = $this->_post('/agreements', [], $body, $headers);

        return $raw ? $response : $response['id'];
    }

    /**
     * Retrieves the URL for the e-sign page for the current
     * signer(s) of an agreement.
     *
     * @param string $agreementId
     * @param bool $raw
     * @return array
     */
    public function getSigningUrls(string $agreementId, bool $raw = false)
    {
        $response = $this->_get('/agreements/' . $agreementId . '/signingUrls');

        if (!$raw) {
            $combined = [];

            foreach ($response['signingUrlSetInfos'] as $set) {
                foreach ($set['signingUrls'] as $urls) {
                    $combined[] = $urls;
                }
            }

            return $combined;
        }

        return $response;
    }

    /**
     * Retrieves a single combined PDF document for the
     * documents associated with an agreement.
     *
     * @param string $agreementId
     * @param null $saveTo path|resource|StreamInterface
     * @param array $options
     * @return bool
     */
    public function getCombinedDocument(string $agreementId, $saveTo, array $options = []): bool
    {
        $resolver = new OptionsResolver();

        $resolver->setDefined('versionId')
            ->setAllowedTypes('versionId', 'string');

        $resolver->setDefined('participantId')
            ->setAllowedTypes('participantId', 'string');

        $resolver->setDefined('attachSupportingDocuments')
            ->setAllowedTypes('attachSupportingDocuments', 'boolean')
            ->setNormalizer('attachSupportingDocuments', $this->booleanNormalizer());

        $resolver->setDefined('attachAuditReport')
            ->setAllowedTypes('attachAuditReport', 'boolean')
            ->setNormalizer('attachAuditReport',  $this->booleanNormalizer());

        return $this->_get(
            '/agreements/' . $agreementId . '/combinedDocument',
            $resolver->resolve($options),
            ['Accept' => 'application/pdf'],
            $saveTo
        ) === [];
    }

    /**
     * This endpoint can be used by originator/sender of an agreement to
     * transition between the states of agreement. An allowed transition
     * would follow the following sequence: DRAFT -> AUTHORING -> IN_PROCESS -> CANCELLED.
     *
     * @param string $agreementId
     * @param AgreementStateInfoBuilder $builder
     * @return array
     */
    public function transitionState(string $agreementId, AgreementStateInfoBuilder $builder): bool
    {
        $body = json_encode($builder->make(), JSON_PRETTY_PRINT);

        $headers = [
            'Content-Type' => 'application/json'
        ];

        return $this->_put('/agreements/' . $agreementId . '/state', [], $body, $headers) === [];
    }
}