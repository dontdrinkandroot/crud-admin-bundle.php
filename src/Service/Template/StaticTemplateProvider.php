<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;

class StaticTemplateProvider implements TemplateProviderInterface
{
    public function __construct(private readonly string $entityClass, private readonly array $templates)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function provideTemplate(string $entityClass, CrudOperation $crudOperation): string
    {
        if (
            $entityClass !== $this->entityClass
            || !array_key_exists($crudOperation->value, $this->templates)
        ) {
            throw new UnsupportedByProviderException($entityClass, $crudOperation);
        }

        return $this->templates[$crudOperation->value];
    }
}
