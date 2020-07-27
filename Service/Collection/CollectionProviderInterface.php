<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Collection;

use Dontdrinkandroot\CrudAdminBundle\Service\RequestProviderInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface CollectionProviderInterface extends RequestProviderInterface
{
    public function provideCollection(Request $request): ?PaginationInterface;
}
