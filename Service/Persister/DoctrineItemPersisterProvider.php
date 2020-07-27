<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Crud\CrudOperation;
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
    public function supports(Request $request): bool
    {
        return
            null !== $this->managerRegistry->getManagerForClass(RequestAttributes::getEntityClass($request))
            && in_array(
                RequestAttributes::getOperation($request),
                [CrudOperation::CREATE, CrudOperation::UPDATE, CrudOperation::DELETE],
                true
            );
    }

    /**
     * {@inheritdoc}
     */
    public function persist(Request $request): bool
    {
        $entityManager = $this->managerRegistry->getManagerForClass(RequestAttributes::getEntityClass($request));
        assert($entityManager instanceof EntityManagerInterface);

        switch (RequestAttributes::getOperation($request)) {
            case CrudOperation::CREATE:
                $entityManager->persist(RequestAttributes::getData($request));
                $entityManager->flush();
                break;
            case CrudOperation::UPDATE:
                $entityManager->flush();
                break;
            case CrudOperation::DELETE:
                $entityManager->remove(RequestAttributes::getData($request));
                $entityManager->flush();
                break;
        }

        return true;
    }
}
