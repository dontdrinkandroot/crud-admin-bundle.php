<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Pagination;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget\PaginationTargetResolver;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class DefaultPaginationProvider implements PaginationProviderInterface
{
    public function __construct(
        private PaginatorInterface $paginator,
        private PaginationTargetResolver $paginationTargetResolver,
        private FieldDefinitionsResolver $fieldDefinitionsResolver
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsPagination(CrudAdminContext $context): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function provideCollection(CrudAdminContext $context): PaginationInterface
    {
        $paginationTarget = $this->paginationTargetResolver->resolve($context);

        $sortFields = [];
        $fieldDefinitions = Asserted::notNull($this->fieldDefinitionsResolver->resolve($context));
        foreach ($fieldDefinitions as $fieldDefinition) {
            if ($fieldDefinition->isSortable()) {
                $sortFields[] = 'entity.' . $fieldDefinition->getPropertyPath();
            }
        }

        $defaultSortFieldName = null;
        $defaultSortDirection = null;
        if (RequestAttributes::entityClassMatches($context)) {
            $defaultSortFieldName = RequestAttributes::getDefaultSortFieldName($context->getRequest());
            if (null !== $defaultSortFieldName) {
                $defaultSortFieldName = 'entity.' . $defaultSortFieldName;
            }
            $defaultSortDirection = RequestAttributes::getDefaultSortDirection($context->getRequest());
        }

        $limit = null;
        if ($context->getRequest()->query->has('perPage')) {
            $limit = $context->getRequest()->query->getInt('perPage');
        }
        return $this->paginator->paginate(
            $paginationTarget,
            $context->getRequest()->query->getInt('page', 1),
            $limit,
            [
                PaginatorInterface::SORT_FIELD_ALLOW_LIST => $sortFields,
                PaginatorInterface::DEFAULT_SORT_FIELD_NAME => $defaultSortFieldName,
                PaginatorInterface::DEFAULT_SORT_DIRECTION => $defaultSortDirection
            ]
        );
    }
}
