<?php

namespace Dontdrinkandroot\CrudAdminBundle\Controller;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Model\Config\CrudConfig;
use Dontdrinkandroot\CrudAdminBundle\Model\Config\DefaultSortConfig;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\FormType\FormTypeProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\DefaultRouteInfoProvider;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\RouteInfoProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Sort\DefaultSortProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateProviderInterface;

class ConfigurableCrudController extends AbstractCrudController
    implements RouteInfoProviderInterface,
               FormTypeProviderInterface, TemplateProviderInterface,
               FieldDefinitionsProviderInterface, DefaultSortProviderInterface
{
    public function __construct(private readonly CrudConfig $crudConfig)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityClass(): string
    {
        return Asserted::notNull($this->crudConfig->entityClass);
    }

    /**
     * {@inheritdoc}
     */
    public function provideRouteInfo(string $entityClass, CrudOperation $crudOperation): RouteInfo
    {
        if (
            !$this->matches($entityClass)
            || null === ($routeConfig = $this->crudConfig->getRouteConfig())
        ) {
            throw new UnsupportedByProviderException($this->getEntityClass(), $crudOperation);
        }

        $pathPrefix = $routeConfig->getPathPrefix()
            ?? DefaultRouteInfoProvider::getDefaultPathPrefix($this->getEntityClass());
        $namePrefix = $routeConfig->getNamePrefix()
            ?? DefaultRouteInfoProvider::getDefaultNamePrefix($this->getEntityClass());

        return DefaultRouteInfoProvider::getRouteInfo($crudOperation, $namePrefix, $pathPrefix);
    }

    /**
     * {@inheritdoc}
     */
    public function provideTemplate(string $entityClass, CrudOperation $crudOperation): string
    {
        if (
            !$this->matches($entityClass, $crudOperation)
            || null === ($templatesConfig = $this->crudConfig->getTemplatesConfig())
            || null === ($template = $templatesConfig->getByCrudOperation($crudOperation))
        ) {
            throw new UnsupportedByProviderException($this->getEntityClass(), $crudOperation);
        }

        return $template;
    }

    /**
     * {@inheritdoc}
     */
    public function provideFieldDefinitions(string $entityClass, CrudOperation $crudOperation): array
    {
        if (
            !$this->matches($entityClass)
            || null === ($fieldDefinitionsConfig = $this->crudConfig->getFieldDefinitionsConfig())
            || null === ($fieldDefinitions = $fieldDefinitionsConfig->getByCrudOperation($crudOperation))
        ) {
            throw new UnsupportedByProviderException($this->getEntityClass(), $crudOperation);
        }

        return $fieldDefinitions;
    }

    public function provideFormType(string $entityClass): string
    {
        if (
            !$this->matches($entityClass)
            || null === ($formType = $this->crudConfig->getFormType())
        ) {
            throw new UnsupportedByProviderException($this->getEntityClass());
        }

        return $formType;
    }

    /**
     * {@inheritdoc}
     */
    public function provideDefaultSort(string $entityClass): ?DefaultSortConfig
    {
        if (
            !$this->matches($entityClass)
            || null === ($defaultSortConfig = $this->crudConfig->getDefaultSortConfig())
        ) {
            return null;
        }

        return $defaultSortConfig;
    }
}
