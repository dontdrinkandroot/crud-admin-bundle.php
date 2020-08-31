<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class RequestAttributeTranslationDomainProvider implements TranslationDomainProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsTranslationDomain(CrudAdminContext $context): bool
    {
        return RequestAttributes::entityClassMatches($context)
            && null !== RequestAttributes::getTranslationDomain($context->getRequest());
    }

    /**
     * {@inheritdoc}
     */
    public function resolveTranslationDomain(CrudAdminContext $context): ?string
    {
        return RequestAttributes::getTranslationDomain($context->getRequest());
    }
}
