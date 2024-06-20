<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Override;
use RuntimeException;

class DoctrineFieldDefinitionsProvider implements FieldDefinitionsProviderInterface
{
    public function __construct(
        private readonly ManagerRegistry $managerRegistry
    ) {
    }

    #[Override]
    public function provideFieldDefinitions(string $entityClass): ?array
    {
        $entityManager = Asserted::instanceOfOrNull(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );
        if (null === $entityManager) {
            return null;
        }

        $classMetadata = $entityManager->getClassMetadata($entityClass);
        $fields = array_keys($classMetadata->fieldMappings);

        $fieldDefinitions = [];
        foreach ($fields as $field) {
            if ($classMetadata->hasField($field)) {
                $fieldMapping = $classMetadata->fieldMappings[$field];
                $crudOperations = CrudOperation::all();
                /* Exclude auto generated id fields for CREATE and UPDATE */
                if (
                    true === ($fieldMapping->id ?? false)
                    && $classMetadata->generatorType !== ClassMetadata::GENERATOR_TYPE_NONE
                ) {
                    $crudOperations = [CrudOperation::LIST, CrudOperation::READ];
                }

                $type = $fieldMapping->type;
                $fieldName = $fieldMapping->fieldName;
                $filterable = false;
                if (in_array($type, ['string', 'integer'])) {
                    $filterable = true;
                }
                $fieldDefinitions[$fieldName] = new FieldDefinition(
                    propertyPath: $fieldName,
                    displayType: $type,
                    crudOperations: $crudOperations,
                    sortable: true,
                    filterable: $filterable
                );
            } elseif ($classMetadata->hasAssociation($field)) {
                $associationMapping = $classMetadata->associationMappings[$field];
                $fieldName = $associationMapping->fieldName;
                $fieldDefinitions[$fieldName] = new FieldDefinition(
                    propertyPath: $fieldName,
                    displayType: 'string',
                    crudOperations: CrudOperation::all(),
                    sortable: false,
                    filterable: false
                );
            } else {
                throw new RuntimeException('Could not resolve field definition for ' . $field);
            }
        }

        return $fieldDefinitions;
    }
}
