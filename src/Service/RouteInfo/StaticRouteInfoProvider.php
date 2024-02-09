<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Override;

class StaticRouteInfoProvider implements RouteInfoProviderInterface
{
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
