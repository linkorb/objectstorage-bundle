<?php

namespace LinkORB\ObjectStorage;

use ObjectStorage\Adapter\EncryptedStorageAdapter;
use ObjectStorage\Adapter\PlaintextStorageKeyEncryptedStorageAdapter;
use ObjectStorage\Adapter\StorageAdapterInterface;

class AdapterFactory
{
    public static function create(
        string $type,
        array $config,
        array $cryptoConfig = []
    ): StorageAdapterInterface {
        $class = '\\ObjectStorage\\Adapter\\' . \ucfirst($type) . 'Adapter';
        if (!\class_exists($class)) {
            throw new \RuntimeException(
                "Unable to create a \"{$type}\" Storage Adapter because the class ({$class}) does not exist or could not be loaded."
            );
        }

        $storageAdapter = $class::build($config);

        if (empty($cryptoConfig)) {
            return $storageAdapter;
        }

        $encryptedAdapterConfig = [
            EncryptedStorageAdapter::CFG_STORAGE_ADAPTER => $storageAdapter,
            EncryptedStorageAdapter::CFG_ENCRYPTION_KEY_PATH => $cryptoConfig['encryption_key_path'],
        ];

        if (false === $cryptoConfig['encrypt_storage_keys']) {
            return PlaintextStorageKeyEncryptedStorageAdapter::build($encryptedAdapterConfig);
        }

        return EncryptedStorageAdapter::build($encryptedAdapterConfig);
    }
}
