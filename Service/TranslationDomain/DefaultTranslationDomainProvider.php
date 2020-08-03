<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultTranslationDomainProvider implements TranslationDomainProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(CrudAdminContext $context): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveTranslationDomain(CrudAdminContext $context): ?string
    {
        return 'DdrCrudAdmin';
    }
}
