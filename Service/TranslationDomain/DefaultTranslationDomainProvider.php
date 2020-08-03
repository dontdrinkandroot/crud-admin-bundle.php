<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultTranslationDomainProvider implements TranslationDomainProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(string $entityClass, string $crudOperation, Request $request): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveTranslationDomain(string $entityClass, string $crudOperation, Request $request): ?string
    {
        return 'DdrCrudAdmin';
    }
}
