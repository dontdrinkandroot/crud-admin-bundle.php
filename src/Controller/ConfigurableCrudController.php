<?php

namespace Dontdrinkandroot\CrudAdminBundle\Controller;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\DefaultSort;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\FormType\FormTypeProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\DefaultRouteInfoProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\RouteInfoProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Sort\DefaultSortProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateProviderInterface;
use Override;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @template T of object
 * @extends AbstractCrudController<T>
 */
abstract class ConfigurableCrudController extends AbstractCrudController
    implements RouteInfoProviderInterface, FormTypeProviderInterface, TemplateProviderInterface, DefaultSortProviderInterface, FieldDefinitionsProviderInterface
{
    #[Override]
    public function provideRouteInfo(string $entityClass, CrudOperation $crudOperation): RouteInfo|false|null
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

    #[Override]
    public function provideDefaultSort(string $entityClass): ?DefaultSort
    {
        if ($entityClass !== $this->getEntityClass()) {
            return null;
        }

        return $this->getDefaultSort();
    }

    #[Override]
    public function provideFieldDefinitions(string $entityClass): ?array
    {
        if ($entityClass !== $this->getEntityClass()) {
            return null;
        }

        return $this->getFieldDefinitions();
    }

    protected function getNamePrefix(): string
    {
        return DefaultRouteInfoProvider::getDefaultNamePrefix($this->getEntityClass());
    }

    protected function getPathPrefix(): string
    {
        return DefaultRouteInfoProvider::getDefaultPathPrefix($this->getEntityClass());
    }

    /**
     * @return class-string<FormTypeInterface>|null
     */
    protected function getFormType(): ?string
    {
        return null;
    }

    protected function getTemplate(CrudOperation $crudOperation): ?string
    {
        return null;
    }

    protected function getDefaultSort(): ?DefaultSort
    {
        return null;
    }

    /**
     * @return array<array-key, FieldDefinition>|null
     */
    protected function getFieldDefinitions(): ?array {
        return null;
    }
}
