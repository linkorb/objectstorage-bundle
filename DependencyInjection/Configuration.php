<?php

namespace LinkORB\ObjectStorage\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('object_storage');

        $rootNode
            ->children()
                ->arrayNode('adapter')
                    ->isRequired()
                    ->children()
                        ->arrayNode('bergen')
                            ->children()
                                ->scalarNode('host')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('username')
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('password')
                                    ->cannotBeEmpty()
                                ->end()
                                ->booleanNode('secure')
                                    ->defaultTrue()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('file')
                            ->children()
                                ->scalarNode('path')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('gridfs')
                            ->children()
                                ->scalarNode('dbname')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('server')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('pdo')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('dsn')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('username')
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('password')
                                ->end()
                                ->scalarNode('tablename')
                                    ->defaultValue('objectstorage')
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('s3')
                            ->children()
                                ->scalarNode('key')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('secret')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('bucketname')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
