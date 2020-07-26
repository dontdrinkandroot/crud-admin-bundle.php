<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface ProviderInterface
{
    public function supports(CrudAdminRequest $request): bool;
}
