<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

/**
 * @template T of object
 */
interface RouteInfoProviderInterface extends ProviderInterface
{
    /**
     * @param class-string<T> $entityClass
     */
    public function provideRouteInfo(string $entityClass, CrudOperation $crudOperation): RouteInfo|false|null;
}
