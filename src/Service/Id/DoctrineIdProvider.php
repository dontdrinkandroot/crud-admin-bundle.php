<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Id;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class DoctrineIdProvider implements IdProviderInterface
{
    public function __construct(
        private ManagerRegistry $managerRegistry,
        private PropertyAccessorInterface $propertyAccessor
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsId(CrudOperation $crudOperation, string $entityClass, object $entity): bool
    {
        return null !== $this->managerRegistry->getManagerForClass(ClassUtils::getClass($entity));
    }

    /**
     * {@inheritdoc}
     */
    public function provideId(CrudOperation $crudOperation, string $entityClass, object $entity): mixed
    {
        $realEntityClass = ClassUtils::getClass($entity);
        $entityManager = Asserted::instanceOf(
            $this->managerRegistry->getManagerForClass($realEntityClass),
            EntityManagerInterface::class
        );
        $classMetadata = $entityManager->getClassMetadata($realEntityClass);

        $identifiers = $classMetadata->identifier;
        if (1 === count($identifiers)) {
            return $this->propertyAccessor->getValue($entity, $identifiers[0]);
        }

        return null;
    }
}
