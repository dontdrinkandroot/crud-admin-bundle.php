<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Crud\CrudOperation;
use Symfony\Component\HttpFoundation\Request;

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
    public function supports(string $entityClass, string $crudOperation, Request $request): bool
    {
        return CrudOperation::LIST === $crudOperation
            && null !== $this->managerRegistry->getManagerForClass($entityClass);
    }

    /**
     * {@inheritdoc}
     */
    public function providePaginationTarget(string $entityClass, string $crudOperation, Request $request): ?QueryBuilder
    {
        $entityManager = $this->managerRegistry->getManagerForClass($entityClass);
        assert($entityManager instanceof EntityManagerInterface);

        return $entityManager->createQueryBuilder()
            ->select('entity')
            ->from($entityClass, 'entity');
    }
}
