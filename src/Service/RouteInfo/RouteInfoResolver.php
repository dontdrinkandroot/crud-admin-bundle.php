<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Override;

/**
 * @extends AbstractProviderService<RouteInfoProviderInterface>
 */
class RouteInfoResolver extends AbstractProviderService implements RouteInfoResolverInterface
{
    #[Override]
    public function resolveRouteInfo(string $entityClass, CrudOperation $crudOperation): ?RouteInfo
    {
        foreach ($this->providers as $provider) {
            $routeInfo = $provider->provideRouteInfo($entityClass, $crudOperation);
            if (null !== $routeInfo) {
                return $routeInfo;
            }
        }

        return null;
    }
}
