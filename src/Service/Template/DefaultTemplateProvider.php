<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\Common\CrudOperation;

class DefaultTemplateProvider implements TemplateProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function provideTemplate(string $entityClass, CrudOperation $crudOperation): string
    {
        $prefix = '@DdrCrudAdmin/';

        return match ($crudOperation) {
            CrudOperation::LIST => $prefix . 'list.html.twig',
            CrudOperation::READ => $prefix . 'read.html.twig',
            CrudOperation::CREATE, CrudOperation::UPDATE => $prefix . 'update.html.twig',
            CrudOperation::DELETE => $prefix . 'delete.html.twig',
        };
    }
}
