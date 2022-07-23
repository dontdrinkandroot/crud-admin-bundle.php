<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

class TranslationDomainResolver extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param string          $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return ?string
     */
    public function resolve(string $crudOperation, string $entityClass): ?string
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
