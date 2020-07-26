<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\ItemProvider;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;

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
    public function supports(CrudAdminRequest $request): bool
    {
        return null !== $this->managerRegistry->getManagerForClass($request->getEntityClass());
    }

    /**
     * {@inheritdoc}
     */
    public function provideItem(CrudAdminRequest $request): ?object
    {
        $entityClass = $request->getEntityClass();
        $entityManager = $this->managerRegistry->getManagerForClass($entityClass);
        assert($entityManager instanceof EntityManagerInterface);

        return $entityManager->find($entityClass, $request->getId());
    }

}
