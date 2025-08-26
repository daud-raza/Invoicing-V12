<?php
return [
    // the main connection that stores tenants
    'landlord_connection' => env('DB_CONNECTION', 'mysql'),
    // runtime tenant connection key name
    'tenant_connection' => 'tenant',
    // domains that should NOT trigger tenancy (root app)
    'central_domains' => [
        parse_url(env('APP_URL'), PHP_URL_HOST) ?: 'example.test',
        'www.' . (parse_url(env('APP_URL'), PHP_URL_HOST) ?: 'example.test'),
    ],
];