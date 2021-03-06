<?php

namespace Mailer;

use GuzzleHttp\Psr7\Response;
use Http\Factory\Guzzle\ResponseFactory;
use League\Route\RouteGroup;
use League\Route\Router as LeagueRouter;
use League\Route\Strategy\JsonStrategy;
use Mailer\Controller\FieldsController;
use Mailer\Controller\SubscriberController;
use Mailer\Middleware\JsonContentTypeMiddleware;
use Mailer\Middleware\ParsesJsonBodyMiddleware;
use Mailer\Middleware\Validator\FieldValidator;
use Mailer\Middleware\Validator\SubscriberFieldValidator;
use Mailer\Middleware\Validator\SubscriberValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Router
{
    protected LeagueRouter $router;

    public function __construct()
    {
        $this->router = new LeagueRouter();
    }

    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        $path = $request->getUri()->getPath();

        if (strpos($path, '/api') !== false) {
            $this->registerApiRoutes($request);
        } else {
            $this->registerStandardRoutes($request);
        }

        $response = $this->router->dispatch($request);

        return $response;
    }

    private function registerApiRoutes(ServerRequestInterface $request): void
    {
        $responseFactory = new ResponseFactory();
        $strategy = new JsonStrategy($responseFactory);
        $this->router->setStrategy($strategy);

        $this->router->group('/api', function (RouteGroup $route) {
            $route->map('GET', '/subscribers', [SubscriberController::class, 'list']);
            $route->map('GET', '/subscribers/{id:number}', [SubscriberController::class, 'find']);
            $route->map('POST', '/subscribers', [SubscriberController::class, 'create'])
                ->middleware(new ParsesJsonBodyMiddleware())
                ->middleware(new SubscriberValidator());
            $route->map('PUT', '/subscribers/{id:number}', [SubscriberController::class, 'update'])
                ->middleware(new ParsesJsonBodyMiddleware())
                ->middleware(new SubscriberValidator());
            $route->map('DELETE', '/subscribers/{id:number}', [SubscriberController::class, 'delete']);

            $route->map(
                'PUT',
                '/subscribers/{id:number}/fields/{fid:number}',
                [SubscriberController::class, 'updateField']
            )->middleware(new ParsesJsonBodyMiddleware())
                ->middleware(new SubscriberFieldValidator());

            $route->map('GET', '/fields', [FieldsController::class, 'list']);
            $route->map('GET', '/fields/{id:number}', [FieldsController::class, 'find']);
            $route->map('POST', '/fields', [FieldsController::class, 'create'])
                ->middleware(new ParsesJsonBodyMiddleware())
                ->middleware(new FieldValidator());
            $route->map('PUT', '/fields/{id:number}', [FieldsController::class, 'update'])
                ->middleware(new ParsesJsonBodyMiddleware())
                ->middleware(new FieldValidator());
            $route->map('DELETE', '/fields/{id:number}', [FieldsController::class, 'delete']);
        })->middleware(new JsonContentTypeMiddleware());
    }

    private function registerStandardRoutes(ServerRequestInterface $request): void
    {
        $this->router->map(
            'GET',
            '/',
            function (ServerRequestInterface $request): ResponseInterface {
                $response = new Response(403);
                $response->getBody()->write('Not allowed');
                return $response;
            }
        );
    }
}
