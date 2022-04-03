<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Routes;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

interface RoutesProviderInterface extends ProviderInterface
{
    public function supportsRoutes(CrudAdminContext $context): bool;

    public function provideRoutes(CrudAdminContext $context): ?array;
}
