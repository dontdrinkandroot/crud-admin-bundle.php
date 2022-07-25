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
     * @param CrudOperation $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return bool
     */
    public function supportRouteInfo(CrudOperation $crudOperation, string $entityClass): bool;

    /**
     * @template T of object
     *
     * @param CrudOperation $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return RouteInfo
     */
    public function provideRouteInfo(CrudOperation $crudOperation, string $entityClass): RouteInfo;
}
