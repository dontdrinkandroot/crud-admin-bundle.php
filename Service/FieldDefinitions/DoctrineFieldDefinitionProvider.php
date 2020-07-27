<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinitions;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\Utils\ClassNameUtils;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
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
    public function supports(Request $request): bool
    {
        return null !== $this->managerRegistry->getManagerForClass(RequestAttributes::getEntityClass($request));
    }

    /**
     * {@inheritdoc}
     */
    public function provideFieldDefinitions(Request $request): ?array
    {
        $entityClass = RequestAttributes::getEntityClass($request);
        $entityManager = $this->managerRegistry->getManagerForClass($entityClass);
        assert($entityManager instanceof EntityManagerInterface);
        $classMetadata = $entityManager->getClassMetadata($entityClass);

        $className = ClassNameUtils::getShortName($entityClass);

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
