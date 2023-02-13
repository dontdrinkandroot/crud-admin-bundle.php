<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;

class StaticFieldDefinitionsProvider implements FieldDefinitionsProviderInterface
{
    /**
     * @param class-string $entityClass
     * @param array<string, array> $fieldDefinitionConfigurations
     */
    public function __construct(
        private readonly string $entityClass,
        private readonly array $fieldDefinitionConfigurations
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function provideFieldDefinitions(string $entityClass): array
    {
        if (
            $entityClass !== $this->entityClass
            || count($this->fieldDefinitionConfigurations) === 0
        ) {
            throw new UnsupportedByProviderException($entityClass);
        }

        $parsedDefinitions = [];
        foreach ($this->fieldDefinitionConfigurations as $fieldDefinitionConfiguration) {
            /** @var list<CrudOperation> $crudOperations */
            $crudOperations = array_map(
                fn(string $crudOperation) => CrudOperation::from(strtoupper($crudOperation)),
                $fieldDefinitionConfiguration['crud_operations']
            );
            $parsedDefinitions[] = new FieldDefinition(
                propertyPath: $fieldDefinitionConfiguration['property_path'],
                displayType: $fieldDefinitionConfiguration['display_type'],
                crudOperations: $crudOperations,
                formType: $fieldDefinitionConfiguration['form_type'] ?? null,
                sortable: $fieldDefinitionConfiguration['sortable'] ?? false,
                filterable: $fieldDefinitionConfiguration['filterable'] ?? false,
            );
        }

        return $parsedDefinitions;
    }
}
