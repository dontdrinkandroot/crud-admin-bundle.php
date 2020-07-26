<?php

namespace Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler;

use Dontdrinkandroot\CrudAdminBundle\Service\CrudAdminService;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
abstract class AbstractProviderCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(CrudAdminService::class)) {
            return;
        }

        $definition = $container->findDefinition(CrudAdminService::class);

        $taggedServices = $container->findTaggedServiceIds($this->getTagName());
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
            $definition->addMethodCall($this->getMethodCall(), [new Reference($id)]);
        }
    }

    abstract protected function getTagName(): string;

    abstract protected function getMethodCall(): string;
}
