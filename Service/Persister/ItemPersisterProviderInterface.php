<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface ItemPersisterProviderInterface extends ProviderInterface
{
    public function supportsPersist(CrudAdminContext $context);

    public function persist(CrudAdminContext $context);
}
