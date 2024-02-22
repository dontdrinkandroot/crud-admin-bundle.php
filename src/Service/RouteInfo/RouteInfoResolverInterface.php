<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;

interface RouteInfoResolverInterface
{
    /**
     * @param class-string  $entityClass
     */
    public function resolveRouteInfo(string $entityClass, CrudOperation $crudOperation): ?RouteInfo;
}
