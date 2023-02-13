<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Id;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class DoctrineIdProvider implements IdProviderInterface
{
    public function __construct(
        private readonly ManagerRegistry $managerRegistry,
        private readonly PropertyAccessorInterface $propertyAccessor
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function provideId(string $entityClass, CrudOperation $crudOperation, object $entity): mixed
    {
        $realEntityClass = ClassUtils::getClass($entity);
        $entityManager = Asserted::instanceOfOrNull(
            $this->managerRegistry->getManagerForClass($realEntityClass),
            EntityManagerInterface::class
        );
        if (null === $entityManager) {
            return null;
        }
        $classMetadata = $entityManager->getClassMetadata($realEntityClass);

        $identifiers = $classMetadata->identifier;
        if (1 === count($identifiers)) {
            return $this->propertyAccessor->getValue($entity, $identifiers[0]);
        }

        return null;
    }
}
