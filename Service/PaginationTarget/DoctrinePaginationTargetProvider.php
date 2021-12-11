<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DoctrinePaginationTargetProvider implements PaginationTargetProvider
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsPaginationTarget(CrudAdminContext $context): bool
    {
        return CrudOperation::LIST === $context->getCrudOperation()
            && null !== $this->managerRegistry->getManagerForClass($context->getEntityClass());
    }

    /**
     * {@inheritdoc}
     */
    public function providePaginationTarget(CrudAdminContext $context): ?QueryBuilder
    {
        $entityClass = $context->getEntityClass();
        $entityManager = $this->managerRegistry->getManagerForClass($entityClass);
        assert($entityManager instanceof EntityManagerInterface);

        return $entityManager->createQueryBuilder()
            ->select('entity')
            ->from($entityClass, 'entity');
    }
}
