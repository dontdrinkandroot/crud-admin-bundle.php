<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinitionProvider;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;

class DoctrineFieldDefinitionProvider implements FieldDefinitionProviderInterface
{
    private ManagerRegistry $managerRegistry;


    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(CrudAdminRequest $request): bool
    {
        return null !== $this->managerRegistry->getManagerForClass($request->getEntityClass());
    }

    /**
     * {@inheritdoc}
     */
    public function provideFieldDefinitions(CrudAdminRequest $request): ?array
    {
        $entityClass = $request->getEntityClass();
        $entityManager = $this->managerRegistry->getManagerForClass($entityClass);
        assert($entityManager instanceof EntityManagerInterface);
        $classMetadata = $entityManager->getClassMetadata($entityClass);

        $parts = explode("\\", $request->getEntityClass());
        $className = $parts[count($parts) - 1];

        $fieldDefinitions = [];
        foreach ($classMetadata->fieldMappings as $fieldMapping) {
            $fieldDefinitions[] = new FieldDefinition(
                $fieldMapping['fieldName'],
                $className.'.'.$fieldMapping['fieldName'],
                $fieldMapping['type'],
                true
            );
        }

        return $fieldDefinitions;
    }
}
