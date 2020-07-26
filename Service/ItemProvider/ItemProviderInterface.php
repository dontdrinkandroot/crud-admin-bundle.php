<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\ItemProvider;

use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface ItemProviderInterface extends ProviderInterface
{
    public function provideItem(CrudAdminRequest $request): ?object;
}
