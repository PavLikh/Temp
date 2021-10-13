<?php

/**
 * Local configuration.
 *
 * Copy this file to `local.php` and change its settings as required.
 * `local.php` is ignored by git and safe to use for local and sensitive data like usernames and passwords.
 */

declare(strict_types=1);
 
return [
    'database' => [
        'driver' => 'mysql',
        'username' => getenv('MYSQL_USER') ?: '',
        'password' => getenv('MYSQL_PASSWORD') ?: '',
        'host' => getenv('MYSQL_HOST') ?: '',
        'database' => getenv('MYSQL_DATABASE') ?: '',
        'port' => getenv('MYSQL_PORT') ?: 3306,
        'charset' => getenv('MYSQL_CHARSET') ?: 'utf8',
        'collation' => getenv('MYSQL_COLLATION') ?: 'utf8_unicode_ci',
        'prefix' => '',
    ],
];
