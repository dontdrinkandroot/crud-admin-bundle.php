<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Override;

/**
 * @template T of object
 * @implements RouteInfoProviderInterface<T>
 */
class StaticRouteInfoProvider implements RouteInfoProviderInterface
{
    /**
     * @param class-string<T> $entityClass
     */
    public function __construct(
        private readonly string $entityClass,
        private readonly ?string $namePrefix = null,
        private readonly ?string $pathPrefix = null
    ) {
    }

    #[Override]
    public function provideRouteInfo(string $entityClass, CrudOperation $crudOperation): ?RouteInfo
    {
        if ($entityClass !== $this->entityClass) {
            return null;
        }

        $namePrefix = $this->namePrefix ?? DefaultRouteInfoProvider::getDefaultNamePrefix($entityClass);
        $pathPrefix = $this->pathPrefix ?? DefaultRouteInfoProvider::getDefaultPathPrefix($entityClass);

        return DefaultRouteInfoProvider::getRouteInfo($crudOperation, $namePrefix, $pathPrefix);
    }
}
