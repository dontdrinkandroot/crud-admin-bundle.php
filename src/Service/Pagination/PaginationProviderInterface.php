<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Pagination;

use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface PaginationProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     *
     * @return bool
     */
    public function supportsPagination(string $entityClass): bool;

    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     *
     * @return PaginationInterface|null
     */
    public function providePagination(string $entityClass): ?PaginationInterface;
}
