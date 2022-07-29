<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
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
    public function provideItem(string $entityClass, CrudOperation $crudOperation, mixed $id): ?object
    {
        $entityManager = Asserted::instanceOfOrNull(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );
        if (null === $entityManager) {
            throw new UnsupportedByProviderException($entityClass, $crudOperation);
        }

        return $entityManager->find($entityClass, $id);
    }
}
