<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;

class DoctrineItemPersisterProvider implements ItemPersisterProviderInterface
{
    public function __construct(private readonly ManagerRegistry $managerRegistry)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsPersist(CrudOperation $crudOperation, string $entityClass, object $entity): bool
    {
        return
            null !== $this->managerRegistry->getManagerForClass($entityClass)
            && in_array(
                $crudOperation,
                [CrudOperation::CREATE, CrudOperation::UPDATE, CrudOperation::DELETE],
                true
            );
    }

    /**
     * {@inheritdoc}
     */
    public function persist(CrudOperation $crudOperation, string $entityClass, object $entity): void
    {
        $entityManager = Asserted::instanceOf(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );

        switch ($crudOperation) {
            case CrudOperation::CREATE:
                $entityManager->persist($entity);
                $entityManager->flush();
                break;
            case CrudOperation::UPDATE:
                $entityManager->flush();
                break;
            case CrudOperation::DELETE:
                $entityManager->remove($entity);
                $entityManager->flush();
                break;
            default:
                /* Noop */
        }
    }
}
