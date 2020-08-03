<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Routes;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface RoutesProviderInterface extends CrudAdminProviderInterface
{
    public function provideRoutes(CrudAdminContext $context): ?array;
}
