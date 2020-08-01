<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface OperationProviderInterface extends ProviderInterface
{
    public function supports(string $entityClass, string $crudOperation, Request $request): bool;
}
