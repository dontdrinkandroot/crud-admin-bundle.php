<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\QueryBuilder;

use Doctrine\ORM\QueryBuilder;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface QueryBuilderExtensionProviderInterface extends ProviderInterface
{
    /**
     * @param class-string $entityClass
     *
     */
    public function extendQueryBuilder(string $entityClass, QueryBuilder $queryBuilder): void;
}
