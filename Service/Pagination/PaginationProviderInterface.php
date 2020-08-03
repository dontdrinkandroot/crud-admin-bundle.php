<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Pagination;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminProviderInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface PaginationProviderInterface extends CrudAdminProviderInterface
{
    public function provideCollection(CrudAdminContext $context): ?PaginationInterface;
}
