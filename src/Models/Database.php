<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;

class Database
{
    private Capsule $capsule;

    public function __construct(array $config)
    {
        $capsule = new Capsule;
        $config = $config['db'];
        $capsule->addConnection([
            "driver" => 'mysql',
            "host" => $config['host'],
            "database" => $config['db'],
            "username" => $config['user'],
            "password" => $config['password'],
            "charset" => "utf8",
            "collation" => "utf8_unicode_ci",
            "prefix" => "",
        ]);
        $capsule->setEventDispatcher(new Dispatcher(new Container));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        $this->capsule = $capsule;
    }

    public function getCapsule(): Capsule
    {
        return $this->capsule;
    }
}
