<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;

class DoctrineItemProvider implements ItemProviderInterface
{
    public function __construct(private readonly ManagerRegistry $managerRegistry)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsItem(CrudOperation $crudOperation, string $entityClass, mixed $id): bool
    {
        return null !== $this->managerRegistry->getManagerForClass($entityClass);
    }

    /**
     * {@inheritdoc}
     */
    public function provideItem(CrudOperation $crudOperation, string $entityClass, mixed $id): ?object
    {
        $entityManager = Asserted::instanceOf(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );

        return $entityManager->find($entityClass, $id);
    }
}
