<?php

namespace Dontdrinkandroot\CrudAdminBundle\DependencyInjection\Compiler;

use Dontdrinkandroot\CrudAdminBundle\Service\Collection\CollectionResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\ProviderServiceInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleResolver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
abstract class AbstractProviderServiceCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $providerServiceClass = $this->getProviderServiceClass();
        assert(is_a($providerServiceClass, ProviderServiceInterface::class, true));
        if (!$container->has($providerServiceClass)) {
            return;
        }

        $definition = $container->findDefinition($providerServiceClass);

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
            $definition->addMethodCall('addProvider', [new Reference($id)]);
        }
    }

    abstract protected function getProviderServiceClass(): string;

    abstract protected function getTagName(): string;
}
