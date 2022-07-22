<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;

class DoctrinePaginationTargetProvider implements PaginationTargetProvider
{

    public function __construct(private readonly ManagerRegistry $managerRegistry)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsPaginationTarget(string $crudOperation, string $entityClass): bool
    {
        return CrudOperation::LIST === $crudOperation
            && null !== $this->managerRegistry->getManagerForClass($entityClass);
    }

    /**
     * {@inheritdoc}
     */
    public function providePaginationTarget(string $crudOperation, string $entityClass): ?QueryBuilder
    {
        $entityManager = Asserted::instanceOf(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );

        return $entityManager->createQueryBuilder()
            ->select('entity')
            ->from($entityClass, 'entity');
    }
}
