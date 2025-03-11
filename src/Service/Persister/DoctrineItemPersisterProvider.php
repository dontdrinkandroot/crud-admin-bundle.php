<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Override;

/**
 * @template T of object
 * @implements ItemPersisterProviderInterface<T>
 */
class DoctrineItemPersisterProvider implements ItemPersisterProviderInterface
{
    public function __construct(private readonly ManagerRegistry $managerRegistry)
    {
    }

    #[Override]
    public function persist(string $entityClass, CrudOperation $crudOperation, object $entity): bool|null
    {
        if (!in_array(
            $crudOperation,
            [CrudOperation::CREATE, CrudOperation::UPDATE, CrudOperation::DELETE],
            true
        )) {
            return null;
        }

        $entityManager = Asserted::instanceOfOrNull(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );
        if (null === $entityManager) {
            return null;
        }

        switch ($crudOperation) {
            case CrudOperation::CREATE:
                $entityManager->persist($entity);
                $entityManager->flush();
                return true;
            case CrudOperation::UPDATE:
                $entityManager->flush();
                return true;
            case CrudOperation::DELETE:
                $entityManager->remove($entity);
                $entityManager->flush();
                return true;
        }

        /** @phpstan-ignore deadCode.unreachable */
        return null;
    }
}
