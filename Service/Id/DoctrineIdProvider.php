<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Id;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;
use Dontdrinkandroot\Common\Asserted;
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
    public function supportsId(CrudAdminContext $context): bool
    {
        $entity = $context->getEntity();

        return null !== $entity
            && null !== $this->managerRegistry->getManagerForClass(ClassUtils::getClass($entity));
    }

    /**
     * {@inheritdoc}
     */
    public function provideId(CrudAdminContext $context)
    {
        $entity = Asserted::notNull($context->getEntity());
        $entityClass = ClassUtils::getClass($entity);
        $entityManager = Asserted::instanceOf(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );
        $classMetadata = $entityManager->getClassMetadata($entityClass);

        $identifiers = $classMetadata->identifier;
        if (1 === count($identifiers)) {
            return $this->propertyAccessor->getValue($entity, $identifiers[0]);
        }

        return null;
    }
}
