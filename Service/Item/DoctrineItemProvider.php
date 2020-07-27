<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DoctrineItemProvider implements ItemProviderInterface
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
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
    public function provideItem(Request $request): ?object
    {
        $crudAdminRequest = new CrudAdminRequest($request);
        $entityClass = $crudAdminRequest->getEntityClass();
        $entityManager = $this->managerRegistry->getManagerForClass($entityClass);
        assert($entityManager instanceof EntityManagerInterface);

        return $entityManager->find($entityClass, $crudAdminRequest->getId());
    }

}
