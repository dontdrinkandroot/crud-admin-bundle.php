<?php

namespace Dontdrinkandroot\CrudAdminBundle;

use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler\CollectionProviderCompilerPass;
use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler\ItemProviderCompilerPass;
use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler\TitleProviderCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DdrCrudAdminBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TitleProviderCompilerPass());
        $container->addCompilerPass(new ItemProviderCompilerPass());
        $container->addCompilerPass(new CollectionProviderCompilerPass());
    }
}
