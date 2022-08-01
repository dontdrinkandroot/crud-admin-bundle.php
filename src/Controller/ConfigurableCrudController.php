<?php

namespace Dontdrinkandroot\CrudAdminBundle\Controller;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Model\Config\CrudConfig;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateProviderInterface;

class ConfigurableCrudController extends AbstractCrudController
    implements
    TemplateProviderInterface,
    FieldDefinitionsProviderInterface
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
}
