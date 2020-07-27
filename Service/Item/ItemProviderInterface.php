<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Item;

use Dontdrinkandroot\CrudAdminBundle\Service\RequestProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface ItemProviderInterface extends RequestProviderInterface
{
    public function provideItem(Request $request): ?object;
}
