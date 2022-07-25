<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface TranslationDomainProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param CrudOperation          $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return bool
     */
    public function supportsTranslationDomain(CrudOperation $crudOperation, string $entityClass): bool;

    /**
     * @template T of object
     *
     * @param CrudOperation          $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return ?string
     */
    public function resolveTranslationDomain(CrudOperation $crudOperation, string $entityClass): ?string;
}
