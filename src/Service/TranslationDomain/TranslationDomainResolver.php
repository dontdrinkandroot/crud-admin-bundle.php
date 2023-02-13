<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

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
            $translationDomain = $provider->provideTranslationDomain($entityClass);
            if (null !== $translationDomain) {
                return $translationDomain;
            }
        }

        return null;
    }
}
