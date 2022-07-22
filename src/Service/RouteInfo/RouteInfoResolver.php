<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo;

use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Service\AbstractProviderService;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateProviderInterface;

/**
 * @extends AbstractProviderService<RouteInfoProviderInterface>
 */
class RouteInfoResolver extends AbstractProviderService
{
    /**
     * @template T of object
     *
     * @param string          $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return ?RouteInfo
     */
    public function resolve(string $crudOperation, string $entityClass): ?RouteInfo
    {
        foreach ($this->providers as $provider) {
            assert($provider instanceof RouteInfoProviderInterface);
            if ($provider->supportRouteInfo($crudOperation, $entityClass)) {
                return $provider->provideRouteInfo($crudOperation, $entityClass);
            }
        }

        return null;
    }
}
