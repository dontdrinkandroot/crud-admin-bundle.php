<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Query;

use Doctrine\ORM\Query;

interface QueryExtensionProviderInterface
{
    /**
     * @template T of object
     * @param class-string<T> $entityClass
     * @param Query<mixed, T> $query
     */
    public function extendQuery(string $entityClass, Query $query): void;
}
