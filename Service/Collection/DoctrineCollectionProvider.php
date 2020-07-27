<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Collection;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Service\Collection\CollectionProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinitions\FieldDefinitionsResolver;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DoctrineCollectionProvider implements CollectionProviderInterface
{
    private ManagerRegistry $managerRegistry;

    private PaginatorInterface $paginator;

    private FieldDefinitionsResolver $fieldDefinitionsResolver;

    public function __construct(
        ManagerRegistry $managerRegistry,
        PaginatorInterface $paginator,
        FieldDefinitionsResolver $fieldDefinitionsResolver
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->paginator = $paginator;
        $this->fieldDefinitionsResolver = $fieldDefinitionsResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request): bool
    {
        $crudAdminRequest = new CrudAdminRequest($request);

        return null !== $this->managerRegistry->getManagerForClass($crudAdminRequest->getEntityClass());
    }

    /**
     * {@inheritdoc}
     */
    public function provideCollection(Request $request): PaginationInterface
    {
        $crudAdminRequest = new CrudAdminRequest($request);
        $entityClass = $crudAdminRequest->getEntityClass();
        $entityManager = $this->managerRegistry->getManagerForClass($entityClass);
        assert($entityManager instanceof EntityManagerInterface);

        $queryBuilder = $entityManager->createQueryBuilder()
            ->select('entity')
            ->from($entityClass, 'entity');

        $sortFields = [];
        $fieldDefinitions = $this->fieldDefinitionsResolver->resolve($request);
        foreach ($fieldDefinitions as $fieldDefinition) {
            if ($fieldDefinition->isSortable()) {
                $sortFields[] = 'entity.' . $fieldDefinition->getPropertyPath();
            }
        }

        return $this->paginator->paginate(
            $queryBuilder,
            1,
            10,
            [PaginatorInterface::SORT_FIELD_ALLOW_LIST => $sortFields]
        );
    }
}