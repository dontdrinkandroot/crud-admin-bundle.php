<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\CollectionProvider;

use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface CollectionProviderInterface extends ProviderInterface
{
    public function provideCollection(CrudAdminRequest $request): ?PaginationInterface;
}
