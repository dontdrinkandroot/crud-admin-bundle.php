<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;

class DoctrineItemPersisterProvider implements ItemPersisterProviderInterface
{
    public function __construct(private readonly ManagerRegistry $managerRegistry)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function persist(string $entityClass, CrudOperation $crudOperation, object $entity): void
    {
        if (!in_array(
            $crudOperation,
            [CrudOperation::CREATE, CrudOperation::UPDATE, CrudOperation::DELETE],
            true
        )) {
            throw new UnsupportedByProviderException($entityClass, $crudOperation);
        }

        $entityManager = Asserted::instanceOfOrNull(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );
        if (null === $entityManager) {
            throw new UnsupportedByProviderException($entityClass, $crudOperation);
        }

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
        }
    }
}
