<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface RouteInfoProviderInterface extends ProviderInterface
{
    /**
     * @param class-string $entityClass
     */
    public function provideRouteInfo(string $entityClass, CrudOperation $crudOperation): RouteInfo|false|null;
}
