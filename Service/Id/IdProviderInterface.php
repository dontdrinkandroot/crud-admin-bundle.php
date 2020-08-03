<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Id;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminProviderInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface IdProviderInterface extends CrudAdminProviderInterface
{
    public function provideId(CrudAdminContext $context);
}
