<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;

interface RouteInfoResolverInterface
{
    /**
     * @param class-string  $entityClass
     * @param CrudOperation $crudOperation
     *
     * @return ?RouteInfo
     */
    public function resolve(string $entityClass, CrudOperation $crudOperation): ?RouteInfo;
}
