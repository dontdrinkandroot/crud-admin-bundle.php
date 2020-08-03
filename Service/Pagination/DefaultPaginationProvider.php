<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Pagination;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
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
    public function supports(string $entityClass, string $crudOperation, Request $request): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function provideCollection(Request $request): PaginationInterface
    {
        $entityClass = RequestAttributes::getEntityClass($request);
        $crudOperation = RequestAttributes::getOperation($request);

        $paginationTarget = $this->paginationTargetResolver->resolve($entityClass, $crudOperation, $request);

        $sortFields = [];
        $fieldDefinitions = $this->fieldDefinitionsResolver->resolve($request);
        foreach ($fieldDefinitions as $fieldDefinition) {
            if ($fieldDefinition->isSortable()) {
                $sortFields[] = 'entity.' . $fieldDefinition->getPropertyPath();
            }
        }

        return $this->paginator->paginate(
            $paginationTarget,
            $request->get('page', 1),
            $request->get('perPage', 10),
            [
                PaginatorInterface::SORT_FIELD_ALLOW_LIST => $sortFields,
                PaginatorInterface::DEFAULT_SORT_FIELD_NAME => RequestAttributes::getDefaultSortFieldName($request),
                PaginatorInterface::DEFAULT_SORT_DIRECTION => RequestAttributes::getDefaultSortDirection($request)
            ]
        );
    }
}