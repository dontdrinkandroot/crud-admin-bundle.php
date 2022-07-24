<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\QueryBuilder\QueryBuilderExtensionProviderInterface;

class DoctrinePaginationTargetProvider implements PaginationTargetProvider
{
    /**
     * @param ManagerRegistry $managerRegistry
     * @param iterable<QueryBuilderExtensionProviderInterface> $queryBuilderExtensionProviders
     */
    public function __construct(
        private readonly ManagerRegistry $managerRegistry,
        private readonly iterable $queryBuilderExtensionProviders
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsPaginationTarget(string $entityClass): bool
    {
        return null !== $this->managerRegistry->getManagerForClass($entityClass);
    }

    /**
     * {@inheritdoc}
     */
    public function providePaginationTarget(string $entityClass): ?QueryBuilder
    {
        $entityManager = Asserted::instanceOf(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );

        $queryBuilder = $entityManager->createQueryBuilder()
            ->select('entity')
            ->from($entityClass, 'entity');

        foreach ($this->queryBuilderExtensionProviders as $queryBuilderExtensionProvider) {
            if ($queryBuilderExtensionProvider->supportsQueryBuilder($entityClass)) {
                $queryBuilderExtensionProvider->extendQueryBuilder($entityClass, $queryBuilder);
            }
        }

        return $queryBuilder;
    }
}
