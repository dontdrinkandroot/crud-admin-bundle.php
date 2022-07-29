<?php

namespace Dontdrinkandroot\CrudAdminBundle\Controller;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Model\Config\CrudConfig;
use Dontdrinkandroot\CrudAdminBundle\Model\Config\DefaultSortConfig;
use Dontdrinkandroot\CrudAdminBundle\Model\RouteInfo;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\DefaultRouteInfoProvider;

class ConfigurableCrudController extends AbstractProvidingCrudController
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
    protected function getRouteInfo(CrudOperation $crudOperation): RouteInfo
    {
        if (null === ($routeConfig = $this->crudConfig->getRouteConfig())) {
            throw new UnsupportedByProviderException($this->getEntityClass(), $crudOperation);
        }

        $pathPrefix = $routeConfig->getPathPrefix() ?? DefaultRouteInfoProvider::getDefaultPathPrefix(
                $this->getEntityClass()
            );
        $namePrefix = $routeConfig->getNamePrefix() ?? DefaultRouteInfoProvider::getDefaultNamePrefix(
                $this->getEntityClass()
            );

        return DefaultRouteInfoProvider::getRouteInfo($crudOperation, $namePrefix, $pathPrefix);
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate(CrudOperation $crudOperation): string
    {
        if (
            null === ($templatesConfig = $this->crudConfig->getTemplatesConfig())
            || null === ($template = $templatesConfig->getByCrudOperation($crudOperation))
        ) {
            throw new UnsupportedByProviderException($this->getEntityClass(), $crudOperation);
        }

        return $template;
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldDefinitions(CrudOperation $crudOperation): array
    {
        if (
            null === ($fieldDefinitionsConfig = $this->crudConfig->getFieldDefinitionsConfig())
            || null === ($fieldDefinitions = $fieldDefinitionsConfig->getByCrudOperation($crudOperation))
        ) {
            throw new UnsupportedByProviderException($this->getEntityClass(), $crudOperation);
        }

        return $fieldDefinitions;
    }

    /**
     * {@inheritdoc}
     */
    protected function getFormType(): string
    {
        if (null === ($formType = $this->crudConfig->getFormType())) {
            throw new UnsupportedByProviderException($this->getEntityClass());
        }

        return $formType;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultSort(string $entityClass): ?DefaultSortConfig
    {
        if (null === ($defaultSortConfig = $this->crudConfig->getDefaultSortConfig())) {
            return null;
        }

        return $defaultSortConfig;
    }
}