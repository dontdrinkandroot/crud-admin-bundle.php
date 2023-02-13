<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Pagination;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\DefaultSort;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget\PaginationTargetResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Sort\DefaultSortProviderInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class DefaultPaginationProvider implements PaginationProviderInterface
{
    /**
     * @param iterable<DefaultSortProviderInterface> $defaultSortProviders
     */
    public function __construct(
        private readonly PaginatorInterface $paginator,
        private readonly PaginationTargetResolver $paginationTargetResolver,
        private readonly FieldDefinitionsResolverInterface $fieldDefinitionsResolver,
        private readonly RequestStack $requestStack,
        private readonly iterable $defaultSortProviders
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function providePagination(string $entityClass): ?PaginationInterface
    {
        $paginationTarget = Asserted::notNull(
            $this->paginationTargetResolver->resolvePaginationTarget($entityClass),
            'No pagination target found.'
        );
        $fieldPrefix = '';
        if ($paginationTarget instanceof QueryBuilder) {
            $fieldPrefix = $paginationTarget->getRootAliases()[0] . '.';
        }
        if ($paginationTarget instanceof Query) {
            $fieldPrefix = 'entity.';
        }

        $sortFields = [];
        $fieldDefinitions = Asserted::notNull(
            $this->fieldDefinitionsResolver->resolveFieldDefinitions($entityClass, CrudOperation::LIST)
        );
        foreach ($fieldDefinitions as $fieldDefinition) {
            if ($fieldDefinition->sortable) {
                $sortFields[] = $fieldPrefix . $fieldDefinition->propertyPath;
            }
        }

        $defaultSortFieldName = null;
        $defaultSortDirection = null;
        $defaultSort = $this->resolveDefaultSort($entityClass);
        if (null !== $defaultSort) {
            $defaultSortFieldName = $fieldPrefix . $defaultSort->field;
            $defaultSortDirection = $defaultSort->order;
            if (!in_array($defaultSortFieldName, $sortFields, true)) {
                $sortFields[] = $defaultSortFieldName;
            }
        }

        $request = Asserted::notNull($this->requestStack->getMainRequest());
        $limit = null;
        if ($request->query->has('perPage')) {
            $limit = $request->query->getInt('perPage');
        }

        return $this->paginator->paginate(
            $paginationTarget,
            $request->query->getInt('page', 1),
            $limit,
            [
                PaginatorInterface::SORT_FIELD_ALLOW_LIST => $sortFields,
                PaginatorInterface::DEFAULT_SORT_FIELD_NAME => $defaultSortFieldName,
                PaginatorInterface::DEFAULT_SORT_DIRECTION => $defaultSortDirection
            ]
        );
    }

    /**
     * @param class-string $entityClass
     */
    private function resolveDefaultSort(string $entityClass): ?DefaultSort
    {
        foreach ($this->defaultSortProviders as $defaultSortProvider) {
            $defaultSort = $defaultSortProvider->provideDefaultSort($entityClass);
            if (null !== $defaultSort) {
                return $defaultSort;
            }
        }

        return null;
    }
}
