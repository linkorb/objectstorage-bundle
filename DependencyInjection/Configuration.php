<?php

namespace LinkORB\ObjectStorage\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('object_storage');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('key_prefix')
                ->end()
                ->scalarNode('adapter')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('The name of the adapter to use. One of "bergen", "file", "gridfs", "pdo" or "s3"')
                    /*
                     * This would be nice to have, but it prevents
                     * the use of an env var for this node
                    ->validate()
                        ->ifNotInArray([
                            'bergen',
                            'file',
                            'gridfs',
                            'pdo',
                            's3',
                        ])
                        ->thenInvalid('Invalid ObjectStorage adapter %s')
                    ->end()
                    */
                ->end()
                ->arrayNode('adapters')
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
                                ->scalarNode('access_key')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('secret_key')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('region')
                                    ->info('See Amazon Simple Storage Service (Amazon S3) regions at https://docs.aws.amazon.com/general/latest/gr/rande.html#s3_region')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('version')
                                    ->cannotBeEmpty()
                                    ->defaultValue('2006-03-01')
                                ->end()
                                ->scalarNode('bucketname')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('canned_acl_for_objects')
                                    ->info('See Canned ACL at https://docs.aws.amazon.com/AmazonS3/latest/dev/acl-overview.html#canned-acl')
                                    /*
                                     * This would be nice to have, but it prevents
                                     * the use of an env var for this node
                                    ->validate()
                                        ->ifNotInArray([
                                            'private',
                                            'public-read',
                                            'public-read-write',
                                            'aws-exec-read',
                                            'authenticated-read',
                                            'bucket-owner-read',
                                            'bucket-owner-full-control',
                                        ])
                                        ->thenInvalid('Invalid S3 canned ACL %s')
                                    ->end()
                                    */
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('storage_encryption')
                    ->children()
                        ->scalarNode('encryption_key_path')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->arrayNode('encrypt_storage_keys')
                            ->canBeEnabled()
                            ->children()
                                ->scalarNode('signing_key_path')
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
