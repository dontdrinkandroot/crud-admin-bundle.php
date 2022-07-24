<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\QueryBuilder;

use Doctrine\ORM\QueryBuilder;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface QueryBuilderExtensionProviderInterface extends ProviderInterface
{
    /**
     * @param class-string $entityClass
     *
     * @return bool
     */
    public function supportsQueryBuilder(string $entityClass): bool;

    /**
     * @param class-string $entityClass
     * @param QueryBuilder $queryBuilder
     *
     * @return void
     */
    public function extendQueryBuilder(string $entityClass, QueryBuilder $queryBuilder): void;
}
