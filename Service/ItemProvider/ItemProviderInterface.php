<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\ItemProvider;

use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface ItemProviderInterface extends ProviderInterface
{
    public function provideItem(CrudAdminRequest $request): ?object;
}
