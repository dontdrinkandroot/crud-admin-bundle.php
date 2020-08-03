<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\NewInstance;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\LegacyOperationProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface NewInstanceProviderInterface extends CrudAdminProviderInterface
{
    public function provideNewInstance(CrudAdminContext $context): ?object;
}
