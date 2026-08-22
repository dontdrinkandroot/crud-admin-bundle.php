<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Query;

use Doctrine\ORM\Query;

/**
 * Extends the Doctrine query of a pagination target.
 */
interface QueryExtensionProviderInterface
{
    /**
     * @template T of object
     * @param class-string<T> $entityClass
     * @param Query<mixed, T> $query
     */
    public function extendQuery(string $entityClass, Query $query): void;
}
