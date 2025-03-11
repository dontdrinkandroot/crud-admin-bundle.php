<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Override;

/**
 * @template P of TranslationDomainProviderInterface
 * @extends AbstractProviderService<P>
 */
class TranslationDomainResolver extends AbstractProviderService implements TranslationDomainResolverInterface
{
    #[Override]
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
