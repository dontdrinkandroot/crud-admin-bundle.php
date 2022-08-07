<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use RuntimeException;

class DoctrineFieldDefinitionsProvider implements FieldDefinitionsProviderInterface
{
    public function __construct(private readonly ManagerRegistry $managerRegistry)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function provideFieldDefinitions(string $entityClass, CrudOperation $crudOperation): array
    {
        $entityManager = Asserted::instanceOfOrNull(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );
        if (null === $entityManager) {
            throw new UnsupportedByProviderException($entityClass, $crudOperation);
        }

        $classMetadata = $entityManager->getClassMetadata($entityClass);
        $fields = array_keys($classMetadata->fieldMappings);

        $fieldDefinitions = [];
        foreach ($fields as $field) {
            if ($classMetadata->hasField($field)) {
                $fieldMapping = $classMetadata->fieldMappings[$field];
                $type = $fieldMapping['type'];
                $fieldName = $fieldMapping['fieldName'];
                $filterable = false;
                if (in_array($type, ['string', 'integer'])) {
                    $filterable = true;
                }

                $fieldDefinitions[$fieldName] = new FieldDefinition(
                    $fieldName,
                    $type,
                    true,
                    $filterable
                );
            } elseif ($classMetadata->hasAssociation($field)) {
                $associationMapping = $classMetadata->associationMappings[$field];
                $fieldName = $associationMapping['fieldName'];
                $fieldDefinitions[$fieldName] = new FieldDefinition(
                    $fieldName,
                    'string',
                    false,
                    false
                );
            } else {
                throw new RuntimeException('Could not resolve field definition for ' . $field);
            }
        }

        return $fieldDefinitions;
    }
}
