<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;

/**
 * @extends AbstractProviderService<RouteInfoProviderInterface>
 */
class RouteInfoResolver extends AbstractProviderService implements RouteInfoResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve(string $entityClass, CrudOperation $crudOperation): ?RouteInfo
    {
        foreach ($this->providers as $provider) {
            assert($provider instanceof RouteInfoProviderInterface);
            try {
                return $provider->provideRouteInfo($entityClass, $crudOperation);
            } catch (UnsupportedByProviderException $e) {
                /* Continue */
            }
        }

        return null;
    }
}
