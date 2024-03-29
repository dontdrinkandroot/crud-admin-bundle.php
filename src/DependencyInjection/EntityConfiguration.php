<?php

namespace Dontdrinkandroot\CrudAdminBundle\DependencyInjection;

use Override;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class EntityConfiguration implements ConfigurationInterface
{
    #[Override]
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ddr_crud_admin_entity');
        $rootNode = $treeBuilder->getRootNode();

        // @formatter:off
        $rootNode->children()
            ->scalarNode('form_type')->end()
            ->scalarNode('translation_domain')->end()
            ->arrayNode('route')
                ->children()
                    ->scalarNode('name_prefix')->end()
                    ->scalarNode('path_prefix')->end()
                ->end()
            ->end()
            ->arrayNode('default_sort')
                ->children()
                    ->scalarNode('field')->end()
                    ->scalarNode('order')->end()
                ->end()
            ->end()
            ->arrayNode('templates')
                ->children()
                    ->scalarNode('list')->end()
                    ->scalarNode('create')->end()
                    ->scalarNode('read')->end()
                    ->scalarNode('update')->end()
                    ->scalarNode('delete')->end()
                ->end()
            ->end()
            ->arrayNode('field_definitions')
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('property_path')->isRequired()->end()
                        ->scalarNode('display_type')->isRequired()->end()
                        ->arrayNode('crud_operations')
                            ->scalarPrototype()
                                ->validate()
                                    ->ifNotInArray(['list', 'create', 'read', 'update', 'delete'])
                                    ->thenInvalid('Invalid CRUD operation %s')
                                ->end()
                            ->end()
                        ->end()
                        ->scalarNode('form_type')->end()
                        ->booleanNode('sortable')->defaultFalse()->end()
                        ->booleanNode('filterable')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end()
        ->end();
        // @formatter:on

        return $treeBuilder;
    }
}
