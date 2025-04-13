<?php

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/migrations',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'dev',
        'dev' => [
            'adapter' => 'mysql',
            'host' => 'db',
            'name' => 'symfony',
            'user' => 'symfony',
            'pass' => 'symfony',
            'port' => '3306',
            'charset' => 'utf8mb4',
        ],
    ],
];
