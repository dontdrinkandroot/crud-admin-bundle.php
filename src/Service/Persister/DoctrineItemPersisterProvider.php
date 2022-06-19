<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;

class DoctrineItemPersisterProvider implements ItemPersisterProviderInterface
{
    public function __construct(private ManagerRegistry $managerRegistry)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsPersist(CrudAdminContext $context): bool
    {
        return
            null !== $context->getEntity()
            && null !== $this->managerRegistry->getManagerForClass($context->getEntityClass())
            && in_array(
                $context->getCrudOperation(),
                [CrudOperation::CREATE, CrudOperation::UPDATE, CrudOperation::DELETE],
                true
            );
    }

    /**
     * {@inheritdoc}
     */
    public function persist(CrudAdminContext $context): bool
    {
        $entityManager = Asserted::instanceOf(
            $this->managerRegistry->getManagerForClass($context->getEntityClass()),
            EntityManagerInterface::class
        );

        switch ($context->getCrudOperation()) {
            case CrudOperation::CREATE:
                $entityManager->persist(Asserted::notNull($context->getEntity()));
                $entityManager->flush();
                break;
            case CrudOperation::UPDATE:
                $entityManager->flush();
                break;
            case CrudOperation::DELETE:
                $entityManager->remove(Asserted::notNull($context->getEntity()));
                $entityManager->flush();
                break;
            default:
                /* Noop */
        }

        return true;
    }
}
