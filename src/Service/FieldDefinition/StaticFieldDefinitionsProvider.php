<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;

class StaticFieldDefinitionsProvider implements FieldDefinitionsProviderInterface
{
    /**
     * @param class-string         $entityClass
     * @param array<string, array> $fieldDefinitionsByCrudOperation
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
            || null === ($fieldDefinitions = $this->getFieldDefinitions($crudOperation))
        ) {
            throw new UnsupportedByProviderException($entityClass, $crudOperation);
        }

        $parsedDefinitions = [];
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

    private function getFieldDefinitions(CrudOperation $crudOperation): ?array {
        $key = $crudOperation->value;
        if (array_key_exists($key, $this->fieldDefinitionsByCrudOperation)) {
            return $this->fieldDefinitionsByCrudOperation[$key];
        }

        $key = strtolower($key);
        return $this->fieldDefinitionsByCrudOperation[$key] ?? null;
    }
}
