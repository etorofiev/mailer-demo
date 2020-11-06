<?php

namespace Mailer\Middleware;

use League\Route\Http\Exception\BadRequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ParsesJsonBodyMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $body = $request->getBody()->getContents();

        $json = json_decode($body, true);

        if (is_null($json)) {
            throw new BadRequestException('Invalid json payload');
        }

        return $handler->handle($request->withParsedBody($json));
    }
}
