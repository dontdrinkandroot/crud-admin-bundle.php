<?php

namespace Dontdrinkandroot\CrudAdminBundle;

use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler\CollectionResolverCompilerPass;
use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler\FieldDefinitionsResolverCompilerPass;
use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler\FormProviderCompilerPass;
use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler\FormResolverCompilerPass;
use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler\ItemPersisterCompilerPass;
use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler\ItemResolverCompilerPass;
use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler\RoutesResolverCompilerPass;
use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler\TemplateResolverCompilerPass;
use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler\TitleResolverCompilerPass;
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

        $container->addCompilerPass(new RoutesResolverCompilerPass());
        $container->addCompilerPass(new ItemResolverCompilerPass());
        $container->addCompilerPass(new CollectionResolverCompilerPass());
        $container->addCompilerPass(new FieldDefinitionsResolverCompilerPass());
        $container->addCompilerPass(new FormResolverCompilerPass());
        $container->addCompilerPass(new TitleResolverCompilerPass());
        $container->addCompilerPass(new TemplateResolverCompilerPass());
        $container->addCompilerPass(new ItemPersisterCompilerPass());
    }
}
