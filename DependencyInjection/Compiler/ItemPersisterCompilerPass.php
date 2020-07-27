<?php

namespace Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler;

use Dontdrinkandroot\CrudAdminBundle\Service\Persister\ItemPersister;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class ItemPersisterCompilerPass extends AbstractProviderServiceCompilerPass
{
    /**
     * {@inheritdoc}
     */
    protected function getProviderServiceClass(): string
    {
        return ItemPersister::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getTagName(): string
    {
        return 'ddr_crud_admin.item_persister_provider';
    }
}
