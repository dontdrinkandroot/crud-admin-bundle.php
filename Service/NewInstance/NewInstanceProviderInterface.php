<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\NewInstance;

use Dontdrinkandroot\CrudAdminBundle\Service\OperationProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface NewInstanceProviderInterface extends OperationProviderInterface
{
    public function provideNewInstance(Request $request): ?object;
}
