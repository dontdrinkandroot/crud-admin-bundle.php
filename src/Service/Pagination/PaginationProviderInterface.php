<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Pagination;

use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @template T of object
 */
interface PaginationProviderInterface extends ProviderInterface
{
    /**
     * @param class-string<T> $entityClass
     * @return PaginationInterface<mixed,T>|null
     */
    public function providePagination(string $entityClass): ?PaginationInterface;
}
