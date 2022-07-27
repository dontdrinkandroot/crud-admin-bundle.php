<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @extends AbstractProviderService<RouteInfoProviderInterface>
 */
class RouteInfoResolver extends AbstractProviderService
{
    /**
     * @param CrudOperation $crudOperation
     * @param class-string  $entityClass
     *
     * @return ?RouteInfo
     */
    public function resolve(CrudOperation $crudOperation, string $entityClass): ?RouteInfo
    {
        foreach ($this->providers as $provider) {
            assert($provider instanceof RouteInfoProviderInterface);
            try {
                return $provider->provideRouteInfo($crudOperation, $entityClass);
            } catch (UnsupportedByProviderException $e) {
                /* Continue */
            }
        }

        return null;
    }
}
