<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\LegacyOperationProviderInterface;
use Symfony\Component\HttpFoundation\Request;

interface PaginationTargetProvider extends CrudAdminProviderInterface
{
    public function providePaginationTarget(CrudAdminContext $context);
}
