<?php

namespace Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler;

use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminService;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RouteProviderCompilerPass extends AbstractProviderCompilerPass
{
    /**
     * {@inheritdoc}
     */
    protected function getTagName(): string
    {
        return 'ddr_crud_admin.route_provider';
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethodCall(): string
    {
        return 'addRouteProvider';
    }
}
