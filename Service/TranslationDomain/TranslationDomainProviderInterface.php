<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\CrudAdminBundle\Service\OperationProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface TranslationDomainProviderInterface extends OperationProviderInterface
{
    public function resolveTranslationDomain(string $entityClass, string $crudOperation, Request $request): ?string;
}
