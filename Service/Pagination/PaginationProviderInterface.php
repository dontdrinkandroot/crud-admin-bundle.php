<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Pagination;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface PaginationProviderInterface extends ProviderInterface
{
    public function supportsPagination(CrudAdminContext $context): bool;

    public function provideCollection(CrudAdminContext $context): ?PaginationInterface;
}
