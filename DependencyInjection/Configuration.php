<?php

namespace Ticketpark\DynamicRoleBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ticketpark_dynamic_role');

        $rootNode
            ->children()
                ->arrayNode('role_table')
                    ->children()
                        ->scalarNode('connection')
                            ->defaultValue('default')
                            ->info('any name configured in doctrine.dbal section')
                        ->end()
                        ->scalarNode('name')
                            ->defaultValue('role')
                            ->info('role table name')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
