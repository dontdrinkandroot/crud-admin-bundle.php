<?php

namespace Dontdrinkandroot\CrudAdminBundle\DependencyInjection;

use Dontdrinkandroot\CrudAdminBundle\Service\Title\DefaultTitleProvider;
use Override;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    #[Override]
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ddr_crud_admin');
        $rootNode = $treeBuilder->getRootNode();

        // @formatter:off
        $rootNode->children()
            ->booleanNode('humanize')->defaultTrue()->end()
            ->enumNode('title_type')
                ->values([DefaultTitleProvider::TYPE_AUTO, DefaultTitleProvider::TYPE_MANUAL])
                ->defaultValue(DefaultTitleProvider::TYPE_AUTO)
            ->end()
        ->end();
        // @formatter:on

        return $treeBuilder;
    }
}
