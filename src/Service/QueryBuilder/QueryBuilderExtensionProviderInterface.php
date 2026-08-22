<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\QueryBuilder;

use Doctrine\ORM\QueryBuilder;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

/**
 * Extends the Doctrine query builder of a pagination target.
 */
interface QueryBuilderExtensionProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     * @param class-string<T> $entityClass
     */
    public function extendQueryBuilder(string $entityClass, QueryBuilder $queryBuilder): void;
}
