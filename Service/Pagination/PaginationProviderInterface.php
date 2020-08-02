<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Pagination;

use Dontdrinkandroot\CrudAdminBundle\Service\OperationProviderInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface PaginationProviderInterface extends OperationProviderInterface
{
    public function provideCollection(Request $request): ?PaginationInterface;
}
