<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

class TranslationDomainResolver extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param CrudOperation          $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return ?string
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
