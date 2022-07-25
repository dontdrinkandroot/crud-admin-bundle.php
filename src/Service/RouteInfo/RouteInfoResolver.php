<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo;

use Dontdrinkandroot\Common\CrudOperation;
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
     * @param CrudOperation          $crudOperation
     * @param class-string<T> $entityClass
     *
     * @return ?RouteInfo
     */
    public function resolve(CrudOperation $crudOperation, string $entityClass): ?RouteInfo
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
