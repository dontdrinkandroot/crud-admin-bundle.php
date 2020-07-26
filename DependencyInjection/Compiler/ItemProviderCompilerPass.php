<?php

namespace Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class ItemProviderCompilerPass extends AbstractProviderCompilerPass
{

    /**
     * {@inheritdoc}
     */
    protected function getTagName(): string
    {
        return 'ddr_crud_admin.item_provider';
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethodCall(): string
    {
        return 'addItemProvider';
    }
}
