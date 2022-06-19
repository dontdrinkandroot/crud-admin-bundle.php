<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;

class RequestAttributeTemplateProvider implements TemplatesProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsTemplates(CrudAdminContext $context): bool
    {
        return RequestAttributes::entityClassMatches($context)
            && null !== RequestAttributes::getTemplates($context->getRequest());
    }

    /**
     * {@inheritdoc}
     */
    public function provideTemplates(CrudAdminContext $context): ?array
    {
        return RequestAttributes::getTemplates($context->getRequest());
    }
}
