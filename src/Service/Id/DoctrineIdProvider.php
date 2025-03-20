<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Id;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Util\DoctrineProxyUtils;
use Override;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * @template T of object
 * @implements IdProviderInterface<T>
 */
class DoctrineIdProvider implements IdProviderInterface
{
    public function __construct(
        private readonly ManagerRegistry $managerRegistry,
        private readonly PropertyAccessorInterface $propertyAccessor
    ) {
    }

    #[Override]
    public function provideId(string $entityClass, CrudOperation $crudOperation, object $entity): mixed
    {
        $entityClass = DoctrineProxyUtils::getClass($entity);
        $entityManager = Asserted::instanceOfOrNull(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );
        if (null === $entityManager) {
            return null;
        }
        $classMetadata = $entityManager->getClassMetadata($entityClass);

        $identifiers = $classMetadata->identifier;
        if (1 === count($identifiers)) {
            return $this->propertyAccessor->getValue($entity, $identifiers[0]);
        }

        return null;
    }

}
