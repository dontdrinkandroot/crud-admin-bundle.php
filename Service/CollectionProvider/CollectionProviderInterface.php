<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\CollectionProvider;

use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface CollectionProviderInterface extends ProviderInterface
{
    public function provideCollection(CrudAdminRequest $request): ?PaginationInterface;
}
