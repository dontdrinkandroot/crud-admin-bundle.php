<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface ItemProviderInterface extends ProviderInterface
{
    public function supportsItem(CrudAdminContext $context): bool;

    public function provideItem(CrudAdminContext $context): ?object;
}
