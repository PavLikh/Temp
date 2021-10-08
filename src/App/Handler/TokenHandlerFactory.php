<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TokenHandlerFactory
{

    public function __invoke(ContainerInterface $container) : RequestHandlerInterface
    {	

        return new TokenHandler($container->get('config')['credentials']);
    }
}
