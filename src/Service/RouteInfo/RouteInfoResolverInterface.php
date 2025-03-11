<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;

interface RouteInfoResolverInterface
{
    /**
     * @template T of object
     * @param class-string<T> $entityClass
     */
    public function resolveRouteInfo(string $entityClass, CrudOperation $crudOperation): ?RouteInfo;
}
