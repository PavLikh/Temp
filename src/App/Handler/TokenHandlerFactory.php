<?php

declare(strict_types=1);

namespace App\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class TokenHandlerFactory
{
    public function __invoke(ContainerInterface $container) : TokenHandler
    {
        return new TokenHandler($container->get(TemplateRendererInterface::class));
    }
}
