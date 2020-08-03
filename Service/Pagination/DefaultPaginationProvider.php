<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Pagination;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Pagination\PaginationProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\PaginationTarget\PaginationTargetResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinitions\FieldDefinitionsResolver;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultPaginationProvider implements PaginationProviderInterface
{
    private PaginatorInterface $paginator;

    private FieldDefinitionsResolver $fieldDefinitionsResolver;

    private PaginationTargetResolver $paginationTargetResolver;

    public function __construct(
        PaginatorInterface $paginator,
        PaginationTargetResolver $paginationTargetResolver,
        FieldDefinitionsResolver $fieldDefinitionsResolver
    ) {
        $this->paginator = $paginator;
        $this->fieldDefinitionsResolver = $fieldDefinitionsResolver;
        $this->paginationTargetResolver = $paginationTargetResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(CrudAdminContext $context): bool
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
        $fieldDefinitions = $this->fieldDefinitionsResolver->resolve($context);
        foreach ($fieldDefinitions as $fieldDefinition) {
            if ($fieldDefinition->isSortable()) {
                $sortFields[] = 'entity.' . $fieldDefinition->getPropertyPath();
            }
        }

        $defaultSortFieldName = null;
        $defaultSortDirection = null;
        if (RequestAttributes::entityClassMatches($context)) {
            $defaultSortFieldName = RequestAttributes::getDefaultSortFieldName($context->getRequest());
            $defaultSortDirection = RequestAttributes::getDefaultSortDirection($context->getRequest());
        }

        return $this->paginator->paginate(
            $paginationTarget,
            $context->getRequest()->get('page', 1),
            $context->getRequest()->get('perPage', 10),
            [
                PaginatorInterface::SORT_FIELD_ALLOW_LIST => $sortFields,
                PaginatorInterface::DEFAULT_SORT_FIELD_NAME => $defaultSortFieldName,
                PaginatorInterface::DEFAULT_SORT_DIRECTION => $defaultSortDirection
            ]
        );
    }
}
