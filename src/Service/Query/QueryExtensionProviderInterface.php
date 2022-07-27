<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Query;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

interface QueryExtensionProviderInterface
{
    /**
     * @param class-string $entityClass
     * @param Query        $query
     *
     * @return void
     */
    public function extendQuery(string $entityClass, Query $query): void;
}
