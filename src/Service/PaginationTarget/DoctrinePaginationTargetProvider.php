<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\CrudAdminBundle\Service\Query\QueryExtensionProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\QueryBuilder\QueryBuilderExtensionProviderInterface;

class DoctrinePaginationTargetProvider implements PaginationTargetProvider
{
    /**
     * @param ManagerRegistry                                  $managerRegistry
     * @param iterable<QueryBuilderExtensionProviderInterface> $queryBuilderExtensionProviders
     * @param iterable<QueryExtensionProviderInterface>        $queryExtensionProviders
     */
    public function __construct(
        private readonly ManagerRegistry $managerRegistry,
        private readonly iterable $queryBuilderExtensionProviders,
        private readonly iterable $queryExtensionProviders
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
    public function providePaginationTarget(string $entityClass): Query
    {
        $entityManager = Asserted::instanceOf(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );

        $queryBuilder = $entityManager->createQueryBuilder()
            ->select('entity')
            ->from($entityClass, 'entity');

        foreach ($this->queryBuilderExtensionProviders as $queryBuilderExtensionProvider) {
            $queryBuilderExtensionProvider->extendQueryBuilder($entityClass, $queryBuilder);
        }

        $query = $queryBuilder->getQuery();

        foreach ($this->queryExtensionProviders as $queryExtensionProvider) {
            $queryExtensionProvider->extendQuery($entityClass, $query);
        }

        return $query;
    }
}
