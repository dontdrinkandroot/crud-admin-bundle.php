<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface CrudAdminProviderInterface extends ProviderInterface
{
    public function supports(CrudAdminContext $context);
}
