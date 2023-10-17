<?php

namespace Dontdrinkandroot\CrudAdminBundle\Controller;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\DefaultRouteInfoProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\RouteInfoProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleProviderInterface;

/**
 * @template T of object
 * @extends AbstractCrudController<T>
 */
abstract class ConfigurableCrudController extends AbstractCrudController
    implements RouteInfoProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function provideRouteInfo(string $entityClass, CrudOperation $crudOperation): ?RouteInfo
    {
       if ($entityClass !== $this->getEntityClass()) {
            return null;
        }

        return DefaultRouteInfoProvider::getRouteInfo($crudOperation, $this->getNamePrefix(), $this->getPathPrefix());
    }

    protected function getNamePrefix(): string
    {
        return DefaultRouteInfoProvider::getDefaultNamePrefix($this->getEntityClass());
    }

    protected function getPathPrefix(): string
    {
        return DefaultRouteInfoProvider::getDefaultPathPrefix($this->getEntityClass());
    }
}
