<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Pagination;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget\PaginationTargetResolver;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class DefaultPaginationProvider implements PaginationProviderInterface
{
    public function __construct(
        private readonly PaginatorInterface $paginator,
        private readonly PaginationTargetResolver $paginationTargetResolver,
        private readonly FieldDefinitionsResolver $fieldDefinitionsResolver,
        private readonly RequestStack $requestStack
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsPagination(string $entityClass): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function providePagination(string $entityClass): ?PaginationInterface
    {
        $paginationTarget = $this->paginationTargetResolver->resolve($entityClass);

        $sortFields = [];
        $fieldDefinitions = Asserted::notNull(
            $this->fieldDefinitionsResolver->resolve(CrudOperation::LIST, $entityClass)
        );
        foreach ($fieldDefinitions as $fieldDefinition) {
            if ($fieldDefinition->sortable) {
                $sortFields[] = 'entity.' . $fieldDefinition->propertyPath;
            }
        }

        $defaultSortFieldName = null;
        $defaultSortDirection = null;

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
}
