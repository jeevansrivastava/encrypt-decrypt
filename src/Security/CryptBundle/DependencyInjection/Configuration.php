<?php

namespace Security\CryptBundle\DependencyInjection;
 
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
 
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('security_crypt');
 
        $rootNode
            ->children()
            ->scalarNode('secret')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('algorithm')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('mode')->isRequired()->cannotBeEmpty()->end()
            ->end();
 
        return $treeBuilder;
    }
}

