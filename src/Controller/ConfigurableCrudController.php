<?php

namespace Dontdrinkandroot\CrudAdminBundle\Controller;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Service\FormType\FormTypeProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\DefaultRouteInfoProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\RouteInfoProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateProviderInterface;
use Override;

/**
 * @template T of object
 * @extends AbstractCrudController<T>
 */
abstract class ConfigurableCrudController extends AbstractCrudController
    implements RouteInfoProviderInterface, FormTypeProviderInterface, TemplateProviderInterface
{
    #[Override]
    public function provideRouteInfo(string $entityClass, CrudOperation $crudOperation): ?RouteInfo
    {
        if ($entityClass !== $this->getEntityClass()) {
            return null;
        }

        return DefaultRouteInfoProvider::getRouteInfo($crudOperation, $this->getNamePrefix(), $this->getPathPrefix());
    }

    #[Override]
    public function provideFormType(string $entityClass): ?string
    {
        if ($entityClass !== $this->getEntityClass()) {
            return null;
        }

        return $this->getFormType();
    }

    #[Override]
    public function provideTemplate(string $entityClass, CrudOperation $crudOperation): ?string
    {
        if ($entityClass !== $this->getEntityClass()) {
            return null;
        }

        return $this->getTemplate($crudOperation);
    }

    protected function getNamePrefix(): string
    {
        return DefaultRouteInfoProvider::getDefaultNamePrefix($this->getEntityClass());
    }

    protected function getPathPrefix(): string
    {
        return DefaultRouteInfoProvider::getDefaultPathPrefix($this->getEntityClass());
    }

    protected function getFormType(): ?string
    {
        return null;
    }

    protected function getTemplate(CrudOperation $crudOperation): ?string
    {
        return null;
    }
}
