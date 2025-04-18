<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\EntityNotFoundException;
use Override;

/**
 * @template T of object
 * @implements ItemProviderInterface<T>
 */
class DoctrineItemProvider implements ItemProviderInterface
{
    public function __construct(private readonly ManagerRegistry $managerRegistry)
    {
    }

    #[Override]
    public function provideItem(string $entityClass, CrudOperation $crudOperation, mixed $id): ?object
    {
        $entityManager = Asserted::instanceOfOrNull(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );
        if (null === $entityManager) {
            return null;
        }

        $entity = $entityManager->find($entityClass, $id);
        if (null === $entity) {
            throw new EntityNotFoundException($entityClass, $crudOperation, $id);
        }

        return $entity;
    }
}
