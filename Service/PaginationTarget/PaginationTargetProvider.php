<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

use Dontdrinkandroot\CrudAdminBundle\Service\OperationProviderInterface;
use Symfony\Component\HttpFoundation\Request;

interface PaginationTargetProvider extends OperationProviderInterface
{
    public function providePaginationTarget(string $entityClass, string $crudOperation, Request $request);
}
