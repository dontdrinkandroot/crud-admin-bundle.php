<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Pagination;

use Knp\Component\Pager\Pagination\PaginationInterface;

interface PaginationResolverInterface
{
    /**
     * @template T of object
     * @param class-string<T> $entityClass
     * @return PaginationInterface<mixed,T>|null
     */
    public function resolvePagination(string $entityClass): ?PaginationInterface;
}
