<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\CollectionProvider;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DoctrineCollectionProvider implements CollectionProviderInterface
{
    private ManagerRegistry $managerRegistry;

    private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $managerRegistry, PaginatorInterface $paginator)
    {
        $this->managerRegistry = $managerRegistry;
        $this->paginator = $paginator;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(CrudAdminRequest $request): bool
    {
        return null !== $this->managerRegistry->getManagerForClass($request->getEntityClass());
    }

    /**
     * {@inheritdoc}
     */
    public function provideCollection(CrudAdminRequest $request): PaginationInterface
    {
        $entityClass = $request->getEntityClass();
        $entityManager = $this->managerRegistry->getManagerForClass($entityClass);
        assert($entityManager instanceof EntityManagerInterface);

        $queryBuilder = $entityManager->createQueryBuilder()
            ->select('entity')
            ->from($entityClass, 'entity');

        $sortFields = [];
        $fieldDefinitions = $request->getFieldDefinitions();
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
