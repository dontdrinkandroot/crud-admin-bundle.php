<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\RouteProvider;

use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface RouteProviderInterface extends ProviderInterface
{
    public function provideRoutes(CrudAdminRequest $request): ?array;
}
