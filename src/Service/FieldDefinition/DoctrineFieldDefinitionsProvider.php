<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use RuntimeException;
use Symfony\Contracts\Translation\TranslatorInterface;

class DoctrineFieldDefinitionsProvider implements FieldDefinitionsProviderInterface
{
    public function __construct(private ManagerRegistry $managerRegistry, private TranslatorInterface $translator)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsFieldDefinitions(string $crudOperation, string $entityClass): bool
    {
        return null !== $this->managerRegistry->getManagerForClass($entityClass);
    }

    /**
     * {@inheritdoc}
     */
    public function provideFieldDefinitions(string $crudOperation, string $entityClass): array
    {
        $entityManager = Asserted::instanceOf(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );
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
