<?php

namespace Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler;

class FieldDefinitionProviderCompilerPass extends AbstractProviderCompilerPass
{
    /**
     * {@inheritdoc}
     */
    protected function getTagName(): string
    {
        return 'ddr_crud_admin.field_definition_provider';
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethodCall(): string
    {
        return 'addFieldDefinitionProvider';
    }
}
