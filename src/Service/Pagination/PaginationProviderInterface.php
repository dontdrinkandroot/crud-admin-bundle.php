<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Pagination;

use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface PaginationProviderInterface extends ProviderInterface
{
    /**
     * @param class-string $entityClass
     *
     * @return PaginationInterface|null
     */
    public function providePagination(string $entityClass): ?PaginationInterface;
}
