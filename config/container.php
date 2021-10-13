<?php

declare(strict_types=1);

use Laminas\ServiceManager\ServiceManager;
use Illuminate\Database\Capsule\Manager as Capsule;

// Load configuration
$config = require __DIR__ . '/config.php';

$dependencies                       = $config['dependencies'];
$dependencies['services']['config'] = $config;

$capsule = new Capsule;
$capsule->addConnection($config['database']);

// Build container
return new ServiceManager($dependencies);
