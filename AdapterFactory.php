<?php

namespace LinkORB\ObjectStorage;

use ObjectStorage\Adapter\AbstractEncryptedStorageAdapter;
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

        $config = $config[$type];

        $storageAdapter = $class::build($config);

        if (empty($cryptoConfig)) {
            return $storageAdapter;
        }

        $encryptedAdapterConfig = [
            AbstractEncryptedStorageAdapter::CFG_STORAGE_ADAPTER => $storageAdapter,
            AbstractEncryptedStorageAdapter::CFG_ENCRYPTION_KEY_PATH => $cryptoConfig['encryption_key_path'],
        ];

        if (false === $cryptoConfig['encrypt_storage_keys']['enabled']) {
            return PlaintextStorageKeyEncryptedStorageAdapter::build($encryptedAdapterConfig);
        }

        $encryptedAdapterConfig[AbstractEncryptedStorageAdapter::CFG_AUTHENTICATION_KEY_PATH] = $cryptoConfig['encrypt_storage_keys']['signing_key_path'];

        return EncryptedStorageAdapter::build($encryptedAdapterConfig);
    }
}
