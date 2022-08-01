<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;

class StaticRouteInfoProvider implements RouteInfoProviderInterface
{
    public function __construct(
        private readonly string $entityClass,
        private readonly ?string $namePrefix = null,
        private readonly ?string $pathPrefix = null
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function provideRouteInfo(string $entityClass, CrudOperation $crudOperation): RouteInfo
    {
        if ($entityClass !== $this->entityClass) {
            throw new UnsupportedByProviderException($entityClass, $crudOperation);
        }

        $namePrefix = $this->namePrefix ?? DefaultRouteInfoProvider::getDefaultNamePrefix($entityClass);
        $pathPrefix = $this->pathPrefix ?? DefaultRouteInfoProvider::getDefaultPathPrefix($entityClass);

        return DefaultRouteInfoProvider::getRouteInfo($crudOperation, $namePrefix, $pathPrefix);
    }
}
