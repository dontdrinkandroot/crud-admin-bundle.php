<?php

namespace Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler;

class CollectionProviderCompilerPass extends AbstractProviderCompilerPass
{
    /**
     * {@inheritdoc}
     */
    protected function getTagName(): string
    {
        return 'ddr_crud_admin.collection_provider';
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethodCall(): string
    {
        return 'addCollectionProvider';
    }
}
