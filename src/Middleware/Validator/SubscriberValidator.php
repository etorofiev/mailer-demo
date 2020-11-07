<?php

namespace Mailer\Middleware\Validator;

use League\Route\Http\Exception\BadRequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SubscriberValidator implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // This request checker would normally check for unique column conflicts, min/max length,
        // invalid characters, and many other things, but to implement all these the way Laravel does in
        // form requests for example would take quite a lot of time - so let's keep it simple with only exists()

        $requiredKeys = [
            'name',
            'email',
            'state'
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

        $validStateOptions = ['active', 'unsubscribed', 'junk', 'bounced', 'unconfirmed'];
        if (!empty($state) and !in_array($json['state'], $validStateOptions)) {
            throw new BadRequestException(
                'Invalid state parameter value, valid options are: '
                . implode(', ', $validStateOptions)
            );
        }

        // Sanity check for emails. There will be another one for the domain before we create the record
        if (!empty($json['email'])) {
            $email = $json['email'];
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                throw new BadRequestException(
                    'The provided email is invalid'
                );
            }
        }

        return $handler->handle($request);
    }
}
