<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface ItemPersisterProviderInterface extends ProviderInterface
{
    public function supportsPersist(CrudAdminContext $context);

    public function persist(CrudAdminContext $context);
}
