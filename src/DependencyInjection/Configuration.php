<?php

namespace Dontdrinkandroot\CrudAdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ddr_crud_admin');
        $rootNode = $treeBuilder->getRootNode();

        // @formatter:off
        $rootNode->children()
            ->arrayNode('entity')
                ->useAttributeAsKey('entityClass')
                ->arrayPrototype()
                    ->children()
                    ->end()
                ->end()
            ->end()
        ->end();
        // @formatter:on

        return $treeBuilder;
    }
}
