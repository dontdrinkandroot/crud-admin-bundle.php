<?php

namespace Dontdrinkandroot\CrudAdminBundle;

use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\CrudConfigCompilerPass;
use Dontdrinkandroot\CrudAdminBundle\DependencyInjection\RegisterControllerCompilerPass;
use Override;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DdrCrudAdminBundle extends Bundle
{
    #[Override]
    public function getPath(): string
    {
        return dirname(__DIR__);
    }

    #[Override]
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new CrudConfigCompilerPass());
        $container->addCompilerPass(new RegisterControllerCompilerPass());
    }
}
