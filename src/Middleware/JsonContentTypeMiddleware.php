<?php

namespace Mailer\Middleware;

use GuzzleHttp\Psr7\Header;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JsonContentTypeMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $headers = $request->getHeaders();
        $contentType = $headers['Content-Type'][0];

        if (!empty($contentType) and strtolower($contentType) !== 'application/json') {
            return new Response(
                415,
                ['Content-Type' => 'application/json'],
                json_encode(['status' => 'error', 'description' => 'Invalid content type, valid options are: application/json'])
            );
        }

        return $handler->handle($request);
    }
}