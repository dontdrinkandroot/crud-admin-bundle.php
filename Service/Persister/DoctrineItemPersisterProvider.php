<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Symfony\Component\HttpFoundation\Request;

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
    public function supports(CrudAdminContext $context): bool
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
        $entityManager = $this->managerRegistry->getManagerForClass($context->getEntityClass());
        assert($entityManager instanceof EntityManagerInterface);

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
