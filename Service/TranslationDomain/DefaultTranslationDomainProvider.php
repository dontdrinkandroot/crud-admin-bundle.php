<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultTranslationDomainProvider implements TranslationDomainProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsTranslationDomain(CrudAdminContext $context): bool
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
