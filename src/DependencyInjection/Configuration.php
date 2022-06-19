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
        ->end();
        // @formatter:on

        return $treeBuilder;
    }
}
