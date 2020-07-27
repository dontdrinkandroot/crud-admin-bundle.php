<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Routes;

use Dontdrinkandroot\CrudAdminBundle\Service\RequestProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface RoutesProviderInterface extends RequestProviderInterface
{
    public function provideRoutes(Request $request): ?array;
}
