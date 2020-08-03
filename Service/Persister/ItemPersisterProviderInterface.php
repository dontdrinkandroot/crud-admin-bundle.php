<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Persister;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\LegacyOperationProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface ItemPersisterProviderInterface extends CrudAdminProviderInterface
{
    public function persist(CrudAdminContext $crudAdminContext);
}
