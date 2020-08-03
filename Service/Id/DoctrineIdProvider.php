<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Id;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DoctrineIdProvider implements IdProviderInterface
{
    private ManagerRegistry $managerRegistry;

    private PropertyAccessorInterface $propertyAccessor;

    public function __construct(ManagerRegistry $managerRegistry, PropertyAccessorInterface $propertyAccessor)
    {
        $this->managerRegistry = $managerRegistry;
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(CrudAdminContext $context)
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
        $entity = $context->getEntity();
        $entityClass = ClassUtils::getClass($entity);
        $entityManager = $this->managerRegistry->getManagerForClass($entityClass);
        assert($entityManager instanceof EntityManagerInterface);
        $classMetadata = $entityManager->getClassMetadata($entityClass);

        $identifiers = $classMetadata->identifier;
        if (1 === count($identifiers)) {
            return $this->propertyAccessor->getValue($entity, $identifiers[0]);
        }

        return null;
    }
}
