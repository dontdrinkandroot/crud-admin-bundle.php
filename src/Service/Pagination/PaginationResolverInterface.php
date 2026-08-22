<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Pagination;

use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Resolves the pagination of an entity class.
 */
interface PaginationResolverInterface
{
    /**
     * @template T of object
     * @param class-string<T> $entityClass
     * @return PaginationInterface<mixed,T>|null
     */
    public function resolvePagination(string $entityClass): ?PaginationInterface;
}
