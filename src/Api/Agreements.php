<?php

namespace Olsgreen\AdobeSign\Api;

use Olsgreen\AdobeSign\Api\Builders\AgreementInfoBuilder;

class Agreements extends AbstractApi
{
    public function all(array $parameters = [])
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
            ->setAllowedTypes('showHiddenAgreements', 'boolean');

        return $this->_get('/agreements', $resolver->resolve($parameters));
    }

    public function create(AgreementInfoBuilder $builder, bool $raw = false)
    {
        $body = json_encode($builder->make(), JSON_PRETTY_PRINT);

        $headers = [
            'Content-Type' => 'application/json'
        ];
        
        $response = $this->_post('/agreements', [], $body, $headers);

        return $raw ? $response : $response['id'];
    }

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
}