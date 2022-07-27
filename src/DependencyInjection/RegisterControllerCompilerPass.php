<?php

namespace Dontdrinkandroot\CrudAdminBundle\DependencyInjection;

use Dontdrinkandroot\CrudAdminBundle\Service\CrudControllerRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterControllerCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $controllerRegistry = $container->register(CrudControllerRegistry::class, CrudControllerRegistry::class);

        $ids = $container->findTaggedServiceIds(DdrCrudAdminExtension::TAG_CONTROLLER);
        foreach ($ids as $id => $args) {
            $controllerRegistry->addMethodCall('registerController' , [$id, new Reference($id)]);
        }
    }
}
