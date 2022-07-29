<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Template;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use RuntimeException;

class DefaultTemplateProvider implements TemplateProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function provideTemplate(string $entityClass, CrudOperation $crudOperation): string
    {
        if (!in_array(
            $crudOperation,
            [CrudOperation::LIST, CrudOperation::READ, CrudOperation::CREATE, CrudOperation::UPDATE],
            true
        )) {
            throw new UnsupportedByProviderException($entityClass, $crudOperation);
        }

        $prefix = '@DdrCrudAdmin/';

        return match ($crudOperation) {
            CrudOperation::LIST => $prefix . 'list.html.twig',
            CrudOperation::READ => $prefix . 'read.html.twig',
            CrudOperation::CREATE => $prefix . 'update.html.twig',
            CrudOperation::UPDATE => $prefix . 'update.html.twig',
            default => throw new RuntimeException('Unsupported CrudOperation: ' . $crudOperation->value)
        };
    }
}
