<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinitionProvider;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\Utils\ClassNameUtils;

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

        $className = ClassNameUtils::getShortName($request->getEntityClass());

        $fieldDefinitions = [];
        foreach ($classMetadata->fieldMappings as $fieldMapping) {
            $type = $fieldMapping['type'];
            $fieldName = $fieldMapping['fieldName'];
            $filterable = false;
            if (in_array($type, ['string', 'integer'])) {
                $filterable = true;
            }

            $fieldDefinitions[] = new FieldDefinition(
                $fieldName,
                $className . '.' . $fieldName,
                $type,
                true,
                $filterable
            );
        }

        return $fieldDefinitions;
    }
}
