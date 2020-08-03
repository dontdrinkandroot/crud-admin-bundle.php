<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class RequestAttributeTemplateProvider implements TemplatesProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(CrudAdminContext $context)
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
