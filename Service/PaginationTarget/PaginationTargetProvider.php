<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface PaginationTargetProvider extends ProviderInterface
{
    public function supportsPaginationTarget(CrudAdminContext $context);

    public function providePaginationTarget(CrudAdminContext $context);
}
