<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\Crud\CrudOperation;
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
    public function supports(string $entityClass, string $crudOperation, Request $request): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function provideTemplates(Request $request): ?array
    {
        $prefix = RequestAttributes::getTemplatesPath($request);
        if (null === $prefix) {
            $prefix = '@DdrCrudAdmin/';
        }

        return [
            CrudOperation::LIST => $prefix . 'list.html.twig',
            CrudOperation::READ => $prefix . 'read.html.twig',
            CrudOperation::CREATE => $prefix . 'update.html.twig',
            CrudOperation::UPDATE => $prefix . 'update.html.twig',
        ];
    }
}
