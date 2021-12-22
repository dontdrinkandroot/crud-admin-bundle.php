<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Pagination;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface PaginationProviderInterface extends ProviderInterface
{
    public function supportsPagination(CrudAdminContext $context): bool;

    public function provideCollection(CrudAdminContext $context): ?PaginationInterface;
}
