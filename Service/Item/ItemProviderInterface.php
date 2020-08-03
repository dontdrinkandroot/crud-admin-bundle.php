<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\LegacyOperationProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface ItemProviderInterface extends CrudAdminProviderInterface
{
    public function provideItem(CrudAdminContext $context): ?object;
}
