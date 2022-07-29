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
    public function resolveRouteInfo(string $entityClass, CrudOperation $crudOperation): ?RouteInfo
    {
        foreach ($this->providers as $provider) {
            try {
                return $provider->provideRouteInfo($entityClass, $crudOperation);
            } catch (UnsupportedByProviderException $e) {
                /* Continue */
            }
        }

        return null;
    }
}
