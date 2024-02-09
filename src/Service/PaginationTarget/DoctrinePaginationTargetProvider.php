<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\CrudAdminBundle\Service\Query\QueryExtensionProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\QueryBuilder\QueryBuilderExtensionProviderInterface;
use Override;

class DoctrinePaginationTargetProvider implements PaginationTargetProvider
{
    /**
     * @param iterable<QueryBuilderExtensionProviderInterface> $queryBuilderExtensionProviders
     * @param iterable<QueryExtensionProviderInterface>        $queryExtensionProviders
     */
    public function __construct(
        private readonly ManagerRegistry $managerRegistry,
        private readonly iterable $queryBuilderExtensionProviders,
        private readonly iterable $queryExtensionProviders
    ) {
    }

    #[Override]
    public function providePaginationTarget(string $entityClass): ?Query
    {
        $entityManager = Asserted::instanceOfOrNull(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );
        if (null === $entityManager) {
            return null;
        }

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
