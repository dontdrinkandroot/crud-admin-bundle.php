<?php

namespace Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler;

use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminService;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class ItemResolverCompilerPass extends AbstractProviderServiceCompilerPass
{
    /**
     * {@inheritdoc}
     */
    protected function getProviderServiceClass(): string
    {
        return ItemResolver::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getTagName(): string
    {
        return 'ddr_crud_admin.item_provider';
    }
}
