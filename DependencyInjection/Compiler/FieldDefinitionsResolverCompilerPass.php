<?php

namespace Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler;

use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminService;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinitions\FieldDefinitionsResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class FieldDefinitionsResolverCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(FieldDefinitionsResolver::class)) {
            return;
        }

        $definition = $container->findDefinition(FieldDefinitionsResolver::class);

        $taggedServices = $container->findTaggedServiceIds('ddr_crud_admin.field_definition_provider');
        $prioritizedIds = [];
        foreach ($taggedServices as $id => $tags) {
            $priority = 0;
            if (array_key_exists('priority', $tags[0])) {
                $priority = $tags[0]['priority'];
            }
            $prioritizedIds[$id] = $priority;
        }

        asort($prioritizedIds);
        foreach ($prioritizedIds as $id => $priority) {
            $definition->addMethodCall('addProvider', [new Reference($id)]);
        }
    }
}
