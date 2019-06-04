<?php

namespace LinkORB\ObjectStorage\Test;

use LinkORB\ObjectStorage\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class ConfigurationTest extends TestCase
{
    /**
     * @dataProvider goodConfigurations
     *
     * @param mixed $inputConfig
     * @param mixed $expectedConfig
     */
    public function testGoodConfiguration($inputConfig, $expectedConfig)
    {
        $configuration = new Configuration();

        $node = $configuration->getConfigTreeBuilder()
            ->buildTree();
        $normalizedConfig = $node->normalize($inputConfig);
        $finalizedConfig = $node->finalize($normalizedConfig);

        $this->assertEquals($expectedConfig, $finalizedConfig);
    }

    /**
     * @dataProvider badConfigurations
     *
     * @param mixed $inputConfig
     */
    public function testBadConfiguration($inputConfig)
    {
        $this->expectException(InvalidConfigurationException::class);

        $configuration = new Configuration();

        $node = $configuration->getConfigTreeBuilder()
            ->buildTree();
        $normalizedConfig = $node->normalize($inputConfig);
        $finalizedConfig = $node->finalize($normalizedConfig);
    }

    public function goodConfigurations()
    {
        return [
            'config includes a storage key prefix' => [
                [
                    'key_prefix' => 'some-prefix',
                    'adapter' => 'file',
                    'adapters' => [
                        'file' => [
                            'path' => '/path/to/some/dir',
                        ],
                    ],
                ],
                [
                    'key_prefix' => 'some-prefix',
                    'adapter' => 'file',
                    'adapters' => [
                        'file' => [
                            'path' => '/path/to/some/dir',
                        ],
                    ],
                ],
            ],
            'config for storage key and object encryption' => [
                [
                    'adapter' => 'file',
                    'adapters' => [
                        'file' => [
                            'path' => '/path/to/some/dir',
                        ],
                    ],
                    'storage_encryption' => [
                        'encryption_key_path' => '/path/to/encryption/key',
                        'encrypt_storage_keys' => [
                            'signing_key_path' => '/path/to/signing/key',
                        ],
                    ]
                ],
                [
                    'adapter' => 'file',
                    'adapters' => [
                        'file' => [
                            'path' => '/path/to/some/dir',
                        ],
                    ],
                    'storage_encryption' => [
                        'encryption_key_path' => '/path/to/encryption/key',
                        'encrypt_storage_keys' => [
                            'enabled' => true,
                            'signing_key_path' => '/path/to/signing/key',
                        ],
                    ]
                ],
            ],
            'config for object encryption and plaintext storage keys' => [
                [
                    'adapter' => 'file',
                    'adapters' => [
                        'file' => [
                            'path' => '/path/to/some/dir',
                        ],
                    ],
                    'storage_encryption' => [
                        'encryption_key_path' => '/path/to/encryption/key',
                    ]
                ],
                [
                    'adapter' => 'file',
                    'adapters' => [
                        'file' => [
                            'path' => '/path/to/some/dir',
                        ],
                    ],
                    'storage_encryption' => [
                        'encryption_key_path' => '/path/to/encryption/key',
                        'encrypt_storage_keys' => [
                            'enabled' => false,
                        ],
                    ]
                ],
            ],
            'minimal bergen adapter config' => [
                [
                    'adapter' => 'bergen',
                    'adapters' => [
                        'bergen' => [
                            'host' => 'some-host',
                            'username' => 'some-user',
                            'password' => 'some-password',
                        ],
                    ],
                ],
                [
                    'adapter' => 'bergen',
                    'adapters' => [
                        'bergen' => [
                            'host' => 'some-host',
                            'username' => 'some-user',
                            'password' => 'some-password',
                            'secure' => true,
                        ],
                    ],
                ],
            ],
            'minimal file adapter config' => [
                [
                    'adapter' => 'file',
                    'adapters' => [
                        'file' => [
                            'path' => '/path/to/some/dir',
                        ],
                    ],
                ],
                [
                    'adapter' => 'file',
                    'adapters' => [
                        'file' => [
                            'path' => '/path/to/some/dir',
                        ],
                    ],
                ],
            ],
            'minimal gridfs adapter config' => [
                [
                    'adapter' => 'gridfs',
                    'adapters' => [
                        'gridfs' => [
                            'dbname' => 'some-dbname',
                            'server' => 'some-servername',
                        ],
                    ],
                ],
                [
                    'adapter' => 'gridfs',
                    'adapters' => [
                        'gridfs' => [
                            'dbname' => 'some-dbname',
                            'server' => 'some-servername',
                        ],
                    ],
                ],
            ],
            'minimal pdo adapter config' => [
                [
                    'adapter' => 'pdo',
                    'adapters' => [
                        'pdo' => [
                            'dsn' => 'scheme://some-user:pass@host/database',
                        ],
                    ],
                ],
                [
                    'adapter' => 'pdo',
                    'adapters' => [
                        'pdo' => [
                            'dsn' => 'scheme://some-user:pass@host/database',
                            'tablename' => 'objectstorage'
                        ],
                    ],
                ],
            ],
            'minimal s3 adapter config' => [
                [
                    'adapter' => 's3',
                    'adapters' => [
                        's3' => [
                            'key' => 'some-access-key',
                            'secret' => 'some-access-secret',
                            'bucketname' => 'some-bucketname',
                            'region' => 'some-region-1',
                        ],
                    ],
                ],
                [
                    'adapter' => 's3',
                    'adapters' => [
                        's3' => [
                            'key' => 'some-access-key',
                            'secret' => 'some-access-secret',
                            'bucketname' => 'some-bucketname',
                            'region' => 'some-region-1',
                            'version' => '2006-03-01',
                        ],
                    ],
                ],
            ],
        ];
    }

    public function badConfigurations()
    {
        return [
            'config for storage key and object encryption is missing the required signing key path' => [
                [
                    'adapter' => 'file',
                    'adapters' => [
                        'file' => [
                            'path' => '/path/to/some/dir',
                        ],
                    ],
                    'storage_encryption' => [
                        'encryption_key_path' => '/path/to/encryption/key',
                        'encrypt_storage_keys' => [
                            'enabled' => true,
                        ],
                    ]
                ],
            ],
        ];
    }
}
