<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @extends AbstractProviderService<TranslationDomainProviderInterface>
 */
class TranslationDomainResolver extends AbstractProviderService implements TranslationDomainResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolveTranslationDomain(string $entityClass): ?string
    {
        foreach ($this->providers as $provider) {
            assert($provider instanceof TranslationDomainProviderInterface);
            try {
                return $provider->provideTranslationDomain($entityClass);
            } catch (UnsupportedByProviderException $e) {
                /* Continue */
            }
        }

        return null;
    }
}
