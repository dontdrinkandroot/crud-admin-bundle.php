<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DoctrineItemPersisterProvider implements ItemPersisterProviderInterface
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
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
                $entityManager->persist($context->getEntity());
                $entityManager->flush();
                break;
            case CrudOperation::UPDATE:
                $entityManager->flush();
                break;
            case CrudOperation::DELETE:
                $entityManager->remove($context->getEntity());
                $entityManager->flush();
                break;
            default:
                /* Noop */
        }

        return true;
    }
}
