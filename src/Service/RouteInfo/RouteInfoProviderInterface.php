<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface RouteInfoProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param class-string<T> $entityClass
     *
     * @return RouteInfo|null
     */
    public function provideRouteInfo(string $entityClass, CrudOperation $crudOperation): ?RouteInfo;
}
