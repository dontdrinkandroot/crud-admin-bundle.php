<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\NewInstance;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface NewInstanceProviderInterface extends ProviderInterface
{
    public function supportsNewInstance(CrudAdminContext $context): bool;

    public function provideNewInstance(CrudAdminContext $context): ?object;
}
