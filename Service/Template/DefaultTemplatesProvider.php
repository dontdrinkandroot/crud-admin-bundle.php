<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultTemplatesProvider implements TemplatesProviderInterface
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
    public function provideTemplates(CrudAdminContext $context): ?array
    {
        $prefix = '@DdrCrudAdmin/';
        if (RequestAttributes::entityClassMatches($context)) {
            $requestAttributesPrefix = RequestAttributes::getTemplatesPath($context->getRequest());
            if (null !== $requestAttributesPrefix) {
                $prefix = $requestAttributesPrefix;
            }
        }

        return [
            CrudOperation::LIST => $prefix . 'list.html.twig',
            CrudOperation::READ => $prefix . 'read.html.twig',
            CrudOperation::CREATE => $prefix . 'update.html.twig',
            CrudOperation::UPDATE => $prefix . 'update.html.twig',
        ];
    }
}
