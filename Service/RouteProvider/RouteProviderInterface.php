<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\RouteProvider;

use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface RouteProviderInterface extends ProviderInterface
{
    public function provideRoutes(CrudAdminRequest $request): ?array;
}
