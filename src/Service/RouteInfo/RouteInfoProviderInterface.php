<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo;

use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface RouteInfoProviderInterface extends ProviderInterface
{
    /**
     * @template T of object
     *
     * @param string $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return bool
     */
    public function supportRouteInfo(string $crudOperation, string $entityClass): bool;

    /**
     * @template T of object
     *
     * @param string $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return RouteInfo
     */
    public function provideRouteInfo(string $crudOperation, string $entityClass): RouteInfo;
}
