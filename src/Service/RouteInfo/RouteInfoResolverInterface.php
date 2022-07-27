<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;


interface RouteInfoResolverInterface
{
    /**
     * @param CrudOperation $crudOperation
     * @param class-string  $entityClass
     *
     * @return ?RouteInfo
     */
    public function resolve(CrudOperation $crudOperation, string $entityClass): ?RouteInfo;
}
