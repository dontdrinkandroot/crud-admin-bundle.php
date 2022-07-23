<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\Common\CrudOperation;

class DefaultTemplateProvider implements TemplateProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsTemplate(string $crudOperation, string $entityClass): bool
    {
        return in_array(
            $crudOperation,
            [CrudOperation::LIST, CrudOperation::READ, CrudOperation::CREATE, CrudOperation::UPDATE],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function provideTemplate(string $crudOperation, string $entityClass): string
    {
        $prefix = '@DdrCrudAdmin/';

        return match($crudOperation) {
            CrudOperation::LIST => $prefix . 'list.html.twig',
            CrudOperation::READ => $prefix . 'read.html.twig',
            CrudOperation::CREATE => $prefix . 'update.html.twig',
            CrudOperation::UPDATE => $prefix . 'update.html.twig',
        };
    }
}
