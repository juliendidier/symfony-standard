<?php

namespace Acme\DemoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('acme_demo');

        $rootNode
            ->children()
                ->arrayNode('static')
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')
                ->end()
            ->end()
            ->scalarNode('directory')->defaultNull()->end()
        ;

        return $treeBuilder;
    }
}
