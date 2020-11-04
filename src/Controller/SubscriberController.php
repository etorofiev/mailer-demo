<?php

namespace Mailer\Controller;

use GuzzleHttp\Psr7\Response;
use Mailer\Model\Subscriber;
use Mailer\Service\SubscriberService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class SubscriberController
{
    use ExtractsQueryParameters;

    public function list(RequestInterface $request, array $args): ResponseInterface
    {
        $from = $this->getQueryParameter($request, 'from', FILTER_VALIDATE_INT);
        $limit = $this->getQueryParameter($request, 'limit', FILTER_VALIDATE_INT) ?? $_ENV['DEFAULT_RESULT_LIMIT'];

        $subscribers = Subscriber::get($from, $limit);
        $count = Subscriber::count();

        $values = [
            'total' => $count,
            'from' => $from,
            'limit' => $limit,
            'data' => $subscribers,
        ];

        $response = new Response();
        $response->getBody()->write(json_encode($values));

        return $response;
    }

    public function find(RequestInterface $request, array $args)
    {
        $id = $args['id'];

        $subscriber = Subscriber::find($id);

        $response = new Response();
        $response->getBody()->write(json_encode($subscriber));

        return $response;
    }
}