<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Pagination;

use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface PaginationProviderInterface extends ProviderInterface
{
    /**
     * @param class-string $entityClass
     *
     * @return PaginationInterface|null
     * @throws UnsupportedByProviderException
     */
    public function providePagination(string $entityClass): ?PaginationInterface;
}
