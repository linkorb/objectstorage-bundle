<?php

namespace LinkORB\ObjectStorage\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class ObjectStorageExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.xml');

        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        $adapterDefn = $container->getDefinition('object_storage.adapter');
        $adapterDefn->replaceArgument('$type', $config['adapter']);
        $adapterDefn->replaceArgument('$config', $config['adapters']);
        if (\array_key_exists('storage_encryption', $config)) {
            $adapterDefn->replaceArgument('$cryptoConfig', $config['storage_encryption']);
        }

        if (\array_key_exists('key_prefix', $config)) {
            $container->getDefinition('ObjectStorage\Service')
                ->addMethodCall('setKeyPrefix', [$config['key_prefix']])
            ;
        }
    }
}
