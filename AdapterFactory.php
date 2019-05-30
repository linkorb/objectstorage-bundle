<?php

namespace LinkORB\ObjectStorage;

use ObjectStorage\Adapter\StorageAdapterInterface;

class AdapterFactory
{
    public static function create(string $type, array $config): StorageAdapterInterface
    {
        $class = '\\ObjectStorage\\Adapter\\' . \ucfirst($type) . 'Adapter';
        if (!\class_exists($class)) {
            throw new \RuntimeException(
                "Unable to create a \"{$type}\" Storage Adapter because the class ({$class}) does not exist or could not be loaded."
            );
        }
        return $class::build($config);
    }
}
