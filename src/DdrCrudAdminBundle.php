<?php

namespace Dontdrinkandroot\CrudAdminBundle;

use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\CrudConfigCompilerPass;
use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\RegisterControllerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DdrCrudAdminBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return dirname(__DIR__);
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new CrudConfigCompilerPass());
        $container->addCompilerPass(new RegisterControllerCompilerPass());
    }
}
