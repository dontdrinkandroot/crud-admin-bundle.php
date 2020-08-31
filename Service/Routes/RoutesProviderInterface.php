<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Routes;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface RoutesProviderInterface extends ProviderInterface
{
    public function supportsRoutes(CrudAdminContext $context): bool;

    public function provideRoutes(CrudAdminContext $context): ?array;
}
