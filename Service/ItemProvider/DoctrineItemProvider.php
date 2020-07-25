<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\ItemProvider;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;

class DoctrineItemProvider implements ItemProviderInterface
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(CrudAdminRequest $request): bool
    {
        // TODO: Implement supports() method.
    }

    /**
     * {@inheritdoc}
     */
    public function provideItem(CrudAdminRequest $request): ?object
    {
        // TODO: Implement provideItem() method.
    }

}
