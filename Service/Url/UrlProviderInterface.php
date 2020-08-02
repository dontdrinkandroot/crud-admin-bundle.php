<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\CrudAdminBundle\Service\OperationProviderInterface;
use Symfony\Component\HttpFoundation\Request;

interface UrlProviderInterface extends OperationProviderInterface
{
    public function provideUrl($entityOrClass, string $crudOperation, Request $request): ?string;
}
