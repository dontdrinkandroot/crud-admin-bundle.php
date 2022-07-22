<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface TranslationDomainProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param string          $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return bool
     */
    public function supportsTranslationDomain(string $crudOperation, string $entityClass): bool;

    /**
     * @template T of object
     *
     * @param string          $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return ?string
     */
    public function resolveTranslationDomain(string $crudOperation, string $entityClass): ?string;
}
