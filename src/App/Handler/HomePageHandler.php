<?php

declare(strict_types=1);

namespace App\Handler;

use Aura\Di\Container;
use Chubbyphp\Container\MinimalContainer;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\ServiceManager\ServiceManager;
use Mezzio\LaminasView\LaminasViewRenderer;
use Mezzio\Plates\PlatesRenderer;
use Mezzio\Router;
use Mezzio\Template\TemplateRendererInterface;
use Mezzio\Twig\TwigRenderer;
use Northwoods\Container\InjectorContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class HomePageHandler implements RequestHandlerInterface
{
    /** @var string */
    // private $containerName;

    /** @var Router\RouterInterface */
    // private $router;

    /** @var null|TemplateRendererInterface */
    // private $template;

    // public function __construct(
    //     string $containerName,
    //     Router\RouterInterface $router,
    //     ?TemplateRendererInterface $template = null
    // ) {
    //     $this->containerName = $containerName;
    //     $this->router        = $router;
    //     $this->template      = $template;
    // }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        $target = $request->getQueryParams()['target'] ?? 'World';
        $target = htmlspecialchars($target, ENT_HTML5, 'UTF-8');
        return new HtmlResponse(sprintf(
            '<h1>Hello %s</h1>',
            $target
        ));
    }
}
