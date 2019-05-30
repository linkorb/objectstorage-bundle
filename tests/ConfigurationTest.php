<?php

namespace LinkORB\ObjectStorage\Test;

use LinkORB\ObjectStorage\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    /**
     * @dataProvider goodConfigurations
     *
     * @param mixed $inputConfig
     * @param mixed $expectedConfig
     */
    public function testConfiguration($inputConfig, $expectedConfig)
    {
        $configuration = new Configuration();

        $node = $configuration->getConfigTreeBuilder()
            ->buildTree();
        $normalizedConfig = $node->normalize($inputConfig);
        $finalizedConfig = $node->finalize($normalizedConfig);

        $this->assertEquals($expectedConfig, $finalizedConfig);
    }

    public function goodConfigurations()
    {
        return [
            'config includes a storage key prefix' => [
                [
                    'key_prefix' => 'some-prefix',
                    'adapter' => [
                        'file' => [
                            'path' => '/path/to/some/dir',
                        ],
                    ],
                ],
                [
                    'key_prefix' => 'some-prefix',
                    'adapter' => [
                        'file' => [
                            'path' => '/path/to/some/dir',
                        ],
                    ],
                ],
            ],
            'minimal bergen adapter config' => [
                [
                    'adapter' => [
                        'bergen' => [
                            'host' => 'some-host',
                            'username' => 'some-user',
                            'password' => 'some-password',
                        ],
                    ],
                ],
                [
                    'key_prefix' => '',
                    'adapter' => [
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
                    'adapter' => [
                        'file' => [
                            'path' => '/path/to/some/dir',
                        ],
                    ],
                ],
                [
                    'key_prefix' => '',
                    'adapter' => [
                        'file' => [
                            'path' => '/path/to/some/dir',
                        ],
                    ],
                ],
            ],
            'minimal gridfs adapter config' => [
                [
                    'adapter' => [
                        'gridfs' => [
                            'dbname' => 'some-dbname',
                            'server' => 'some-servername',
                        ],
                    ],
                ],
                [
                    'key_prefix' => '',
                    'adapter' => [
                        'gridfs' => [
                            'dbname' => 'some-dbname',
                            'server' => 'some-servername',
                        ],
                    ],
                ],
            ],
            'minimal pdo adapter config' => [
                [
                    'adapter' => [
                        'pdo' => [
                            'dsn' => 'scheme://some-user:pass@host/database',
                        ],
                    ],
                ],
                [
                    'key_prefix' => '',
                    'adapter' => [
                        'pdo' => [
                            'dsn' => 'scheme://some-user:pass@host/database',
                            'tablename' => 'objectstorage'
                        ],
                    ],
                ],
            ],
            'minimal s3 adapter config' => [
                [
                    'adapter' => [
                        's3' => [
                            'key' => 'some-access-key',
                            'secret' => 'some-access-secret',
                            'bucketname' => 'some-bucketname',
                        ],
                    ],
                ],
                [
                    'key_prefix' => '',
                    'adapter' => [
                        's3' => [
                            'key' => 'some-access-key',
                            'secret' => 'some-access-secret',
                            'bucketname' => 'some-bucketname',
                        ],
                    ],
                ],
            ],
        ];
    }
}
