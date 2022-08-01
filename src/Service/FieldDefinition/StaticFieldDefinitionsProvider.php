<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;

class StaticFieldDefinitionsProvider implements FieldDefinitionsProviderInterface
{
    /**
     * @param class-string $entityClass
     * @param array        $fieldDefinitionsByCrudOperation
     */
    public function __construct(
        private readonly string $entityClass,
        private readonly array $fieldDefinitionsByCrudOperation
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function provideFieldDefinitions(string $entityClass, CrudOperation $crudOperation): array
    {
        if (
            $entityClass !== $this->entityClass
            || !array_key_exists($crudOperation->value, $this->fieldDefinitionsByCrudOperation)
        ) {
            throw new UnsupportedByProviderException($entityClass, $crudOperation);
        }

        $parsedDefinitions = [];
        $fieldDefinitions = $this->fieldDefinitionsByCrudOperation[$crudOperation->value];
        foreach ($fieldDefinitions as $fieldDefinition) {
            $parsedDefinitions[] = new FieldDefinition(
                $fieldDefinition['property_path'],
                $fieldDefinition['type'],
                $fieldDefinition['sortable'] ?? false,
                $fieldDefinition['filterable'] ?? false,
            );
        }

        return $parsedDefinitions;
    }
}
