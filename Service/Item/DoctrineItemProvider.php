<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DoctrineItemProvider implements ItemProviderInterface
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
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
        $entityManager = $this->managerRegistry->getManagerForClass($entityClass);
        assert($entityManager instanceof EntityManagerInterface);

        return $entityManager->find($entityClass, RequestAttributes::getId($context->getRequest()));
    }
}
