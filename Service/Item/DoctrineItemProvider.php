<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;

class DoctrineItemProvider implements ItemProviderInterface
{
    public function __construct(private ManagerRegistry $managerRegistry)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsItem(CrudAdminContext $context): bool
    {
        return null !== $this->managerRegistry->getManagerForClass($context->getEntityClass());
    }

    /**
     * {@inheritdoc}
     */
    public function provideItem(CrudAdminContext $context): ?object
    {
        $entityClass = $context->getEntityClass();
        $entityManager = Asserted::instanceOf(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );

        return $entityManager->find($entityClass, RequestAttributes::getId($context->getRequest()));
    }
}
