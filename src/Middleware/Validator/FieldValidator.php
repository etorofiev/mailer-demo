<?php

namespace Mailer\Middleware\Validator;

use League\Route\Http\Exception\BadRequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FieldValidator implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // This request checker would normally check for unique column conflicts, min/max length,
        // invalid characters, and many other things, but to implement all these the way Laravel does in
        // form requests for example would take quite a lot of time - so let's keep it simple with only exists()

        $requiredKeys = [
            'title',
            'type'
        ];

        $json = $request->getParsedBody();

        $missingParamsDiff = array_diff($requiredKeys, array_keys($json));
        $extraParamsDiff = array_diff(array_keys($json), $requiredKeys);

        if ($request->getMethod() === 'POST' and count($missingParamsDiff) > 0) {
            throw new BadRequestException(
                'The following keys are missing from the request: '
                . implode(', ', $missingParamsDiff)
            );
        }

        if (count($extraParamsDiff) > 0) {
            throw new BadRequestException(
                'Too many keys have been provided, please use only the following keys: '
                . implode(', ', $requiredKeys)
            );
        }

        if ($request->getMethod() === 'POST') {
            $validTypeOptions = ['date', 'string', 'int', 'bool'];
            if (!in_array($json['type'], $validTypeOptions)) {
                throw new BadRequestException(
                    'Invalid type parameter value, valid options are: '
                    . implode(', ', $validTypeOptions)
                );
            }
        } elseif ($request->getMethod() === 'PUT' and !empty($json['type'])) {
            throw new BadRequestException('You cannot update the type of an existing field.');
        }

        return $handler->handle($request);
    }
}
