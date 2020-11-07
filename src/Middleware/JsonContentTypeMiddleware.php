<?php

namespace Mailer\Middleware;

use League\Route\Http\Exception\UnsupportedMediaException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JsonContentTypeMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $headers = $request->getHeaders();
        $contentType = null;

        if (!empty($headers) and !empty($headers['Content-Type'])) {
            $contentType = $headers['Content-Type'][0];
        }

        if (!empty($contentType) and strtolower($contentType) !== 'application/json') {
            throw new UnsupportedMediaException('Invalid content type, valid options are: application/json');
        }

        return $handler->handle($request);
    }
}
