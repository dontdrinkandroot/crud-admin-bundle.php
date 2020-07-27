<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttribute;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultTemplateProvider implements TemplateProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(Request $request): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function provide(Request $request): ?string
    {
        switch ($request->attributes->get(RequestAttribute::OPERATION)) {
            case CrudOperation::LIST:
                return '@DdrCrudAdmin/list.html.twig';
            case CrudOperation::READ:
                return '@DdrCrudAdmin/read.html.twig';
            case CrudOperation::UPDATE:
            case CrudOperation::CREATE:
                return '@DdrCrudAdmin/update.html.twig';
        }

        return null;
    }
}