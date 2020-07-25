<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;

interface ProviderInterface
{
    public function supports(CrudAdminRequest $request): bool;
}
