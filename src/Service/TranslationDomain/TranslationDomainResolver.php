<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @extends AbstractProviderService<TranslationDomainProviderInterface>
 */
class TranslationDomainResolver extends AbstractProviderService implements TranslationDomainResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve(CrudOperation $crudOperation, string $entityClass): ?string
    {
        foreach ($this->providers as $provider) {
            assert($provider instanceof TranslationDomainProviderInterface);
            if ($provider->supportsTranslationDomain($crudOperation, $entityClass)) {
                return $provider->resolveTranslationDomain($crudOperation, $entityClass);
            }
        }

        return null;
    }
}
